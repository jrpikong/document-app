<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Document;
use App\Models\LegacyDocumentMap;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class WpDocumentImportService
{
    private array $categoryCache = [];
    private array $userCache = [];

    /**
     * @param array<int, int> $legacyIds
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function importLegacyIds(array $legacyIds, array $options = []): array
    {
        $summary = [
            'processed' => 0,
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'failures' => [],
        ];

        foreach ($legacyIds as $legacyId) {
            $legacyId = (int) $legacyId;
            if ($legacyId <= 0) {
                continue;
            }

            $summary['processed']++;

            try {
                $result = $this->importOne($legacyId, $options);
                $summary[$result] = ($summary[$result] ?? 0) + 1;
            } catch (Throwable $e) {
                $summary['failed']++;
                $summary['failures'][] = [
                    'legacy_id' => $legacyId,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $summary;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function importOne(int $legacyId, array $options): string
    {
        $connection = (string) ($options['connection'] ?? 'wordpress');
        $sourceTable = (string) ($options['source_table'] ?? 'wp2o_wpdatatable_1');
        $sourceName = (string) ($options['source_name'] ?? 'wordpress');
        $defaultStatus = (string) ($options['default_status'] ?? Document::STATUS_APPROVED);
        $updateExisting = (bool) ($options['update_existing'] ?? false);
        $dryRun = (bool) ($options['dry_run'] ?? false);
        $skipFiles = (bool) ($options['skip_files'] ?? false);
        $redownloadFiles = (bool) ($options['redownload_files'] ?? false);

        $legacyRow = DB::connection($connection)
            ->table($sourceTable)
            ->where('wdt_ID', $legacyId)
            ->first();

        if (! $legacyRow) {
            throw new RuntimeException("Legacy row not found in {$sourceTable} for wdt_ID={$legacyId}");
        }

        $legacy = (array) $legacyRow;

        if ($dryRun) {
            // Lightweight dry-run: validate source accessibility without touching local DB.
            return 'imported';
        }

        $defaultUserId = $this->resolveDefaultUserId($options['default_user_email'] ?? null);

        $map = LegacyDocumentMap::query()
            ->where('source', $sourceName)
            ->where('legacy_table', $sourceTable)
            ->where('legacy_id', $legacyId)
            ->first();

        if ($map && ! $updateExisting) {
            return 'skipped';
        }

        $title = $this->resolveTitle($legacy, $legacyId);
        $categoryId = $this->resolveCategoryId((string) ($legacy['category'] ?? ''));
        $uploadedById = $this->resolveUserId((string) ($legacy['wdt_created_by'] ?? ''), $defaultUserId);
        $approvedById = $this->resolveUserId((string) ($legacy['wdt_last_edited_by'] ?? ''), $defaultUserId);

        $createdAt = $this->parseDate($legacy['wdt_created_at'] ?? null) ?? now();
        $updatedAt = $this->parseDate($legacy['wdt_last_edited_at'] ?? null) ?? $createdAt;
        $approvedAt = $updatedAt;
        $approvalNote = $this->normalizeNote((string) ($legacy['note'] ?? ''));

        $fileInfo = $this->prepareFile(
            legacyId: $legacyId,
            fileUrl: (string) ($legacy['file'] ?? ''),
            skipFiles: $skipFiles,
            redownloadFiles: $redownloadFiles
        );

        DB::transaction(function () use (
            $map,
            $sourceName,
            $sourceTable,
            $legacyId,
            $legacy,
            $fileInfo,
            $title,
            $categoryId,
            $defaultStatus,
            $uploadedById,
            $approvedById,
            $approvedAt,
            $approvalNote,
            $createdAt,
            $updatedAt
        ): void {
            $document = $map?->document;
            if (! $document) {
                $document = new Document();
            }

            $document->title = $title;
            $document->category_id = $categoryId;
            $document->file_path = $fileInfo['file_path'];
            $document->original_name = $fileInfo['original_name'];
            $document->status = $defaultStatus;
            $document->uploaded_by = $uploadedById;
            $document->approved_by = $approvedById;
            $document->approved_at = $approvedAt;
            $document->approval_note = $approvalNote;
            $document->created_at = $createdAt;
            $document->updated_at = $updatedAt;
            $document->save();

            $historyNote = $approvalNote ?: 'Imported from legacy WordPress wpDataTables.';
            if (! $map) {
                $document->histories()->create([
                    'user_id' => $approvedById,
                    'from_status' => Document::STATUS_SUBMITTED,
                    'to_status' => $defaultStatus,
                    'note' => $historyNote,
                    'created_at' => $approvedAt,
                    'updated_at' => $approvedAt,
                ]);
            }

            LegacyDocumentMap::query()->updateOrCreate(
                [
                    'source' => $sourceName,
                    'legacy_table' => $sourceTable,
                    'legacy_id' => $legacyId,
                ],
                [
                    'document_id' => $document->id,
                    'legacy_file_url' => $legacy['file'] ?? null,
                    'legacy_payload' => $legacy,
                    'imported_at' => now(),
                ]
            );
        });

        return $map ? 'updated' : 'imported';
    }

    /**
     * @param array<string, mixed> $legacy
     */
    private function resolveTitle(array $legacy, int $legacyId): string
    {
        $title = trim((string) ($legacy['descriptions'] ?? ''));
        if ($title !== '') {
            return Str::limit($title, 255, '');
        }

        $nameFromFile = $this->extractOriginalName((string) ($legacy['file'] ?? ''));
        if ($nameFromFile !== '') {
            return Str::limit($nameFromFile, 255, '');
        }

        return "Legacy Document {$legacyId}";
    }

    private function resolveCategoryId(string $rawCategory): int
    {
        $name = trim($rawCategory);
        if ($name === '') {
            $name = 'Uncategorized';
        }

        $cacheKey = mb_strtolower($name);
        if (isset($this->categoryCache[$cacheKey])) {
            return $this->categoryCache[$cacheKey];
        }

        $category = Category::query()->firstOrCreate(['name' => $name]);
        $this->categoryCache[$cacheKey] = (int) $category->id;

        return (int) $category->id;
    }

    private function resolveDefaultUserId(mixed $defaultUserEmail): int
    {
        if (is_string($defaultUserEmail) && trim($defaultUserEmail) !== '') {
            $user = User::query()->where('email', trim($defaultUserEmail))->first();
            if ($user) {
                return (int) $user->id;
            }
        }

        $user = User::query()->orderBy('id')->first();
        if (! $user) {
            throw new RuntimeException('No users found in local database. Create at least one user before import.');
        }

        return (int) $user->id;
    }

    private function resolveUserId(string $actorName, int $fallbackUserId): int
    {
        $name = trim($actorName);
        if ($name === '') {
            return $fallbackUserId;
        }

        $cacheKey = mb_strtolower($name);
        if (isset($this->userCache[$cacheKey])) {
            return $this->userCache[$cacheKey];
        }

        $query = User::query();
        $lowerName = mb_strtolower($name);
        $user = $query
            ->whereRaw('LOWER(name) = ?', [$lowerName])
            ->orWhereRaw('LOWER(email) = ?', [$lowerName])
            ->first();

        if (! $user && str_contains($name, ',')) {
            $parts = array_map('trim', explode(',', $name, 2));
            if (count($parts) === 2) {
                $reversed = trim($parts[1] . ' ' . $parts[0]);
                $user = User::query()
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($reversed)])
                    ->first();
            }
        }

        $id = $user ? (int) $user->id : $fallbackUserId;
        $this->userCache[$cacheKey] = $id;

        return $id;
    }

    /**
     * @return array{file_path: string, original_name: string}
     */
    private function prepareFile(int $legacyId, string $fileUrl, bool $skipFiles, bool $redownloadFiles): array
    {
        $disk = 'public';
        $originalName = $this->extractOriginalName($fileUrl);
        if ($originalName === '') {
            $originalName = "legacy-{$legacyId}.bin";
        }

        if ($skipFiles) {
            $filePath = "documents/imported/wp/pointers/legacy-{$legacyId}.txt";
            Storage::disk($disk)->put($filePath, trim($fileUrl));

            return [
                'file_path' => $filePath,
                'original_name' => $originalName,
            ];
        }

        if (trim($fileUrl) === '') {
            throw new RuntimeException("Legacy row {$legacyId} has empty file URL.");
        }

        [$year, $month] = $this->extractYearMonthFromUrl($fileUrl);
        $safeName = $this->sanitizeFileName($originalName);
        $filePath = "documents/imported/wp/{$year}/{$month}/{$legacyId}-{$safeName}";

        if (Storage::disk($disk)->exists($filePath) && ! $redownloadFiles) {
            return [
                'file_path' => $filePath,
                'original_name' => $originalName,
            ];
        }

        $response = Http::timeout(120)
            ->retry(2, 500)
            ->get($fileUrl);

        if (! $response->successful()) {
            throw new RuntimeException("Failed to download file URL: {$fileUrl} (HTTP {$response->status()})");
        }

        Storage::disk($disk)->put($filePath, $response->body());

        return [
            'file_path' => $filePath,
            'original_name' => $originalName,
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function extractYearMonthFromUrl(string $url): array
    {
        if (preg_match('#/uploads/(\d{4})/(\d{2})/#', $url, $matches) === 1) {
            return [$matches[1], $matches[2]];
        }

        return [now()->format('Y'), now()->format('m')];
    }

    private function extractOriginalName(string $fileUrl): string
    {
        $path = (string) parse_url($fileUrl, PHP_URL_PATH);
        $name = $path !== '' ? basename($path) : '';
        return urldecode(trim($name));
    }

    private function sanitizeFileName(string $name): string
    {
        $name = str_replace(['\\', '/', ':', '*', '?', '"', '<', '>', '|'], '_', $name);
        $name = preg_replace('/\s+/', '-', $name) ?? $name;
        return trim($name, '. ');
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }
    }

    private function normalizeNote(string $note): ?string
    {
        $note = trim($note);
        if ($note === '') {
            return null;
        }

        $note = preg_replace('/<br\s*\/?>/i', PHP_EOL, $note) ?? $note;
        $note = strip_tags($note);
        $note = html_entity_decode($note, ENT_QUOTES | ENT_HTML5);
        $note = trim($note);

        return $note === '' ? null : Str::limit($note, 65535, '');
    }
}
