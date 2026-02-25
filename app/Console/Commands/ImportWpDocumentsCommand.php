<?php

namespace App\Console\Commands;

use App\Jobs\ImportWpDocumentChunkJob;
use App\Services\WpDocumentImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportWpDocumentsCommand extends Command
{
    protected $signature = 'import:wp-documents
        {--connection=wordpress : Source database connection}
        {--source-table=wp2o_wpdatatable_1 : Source table name}
        {--source-name=wordpress : Source identifier for legacy mapping}
        {--chunk=100 : Chunk size}
        {--id=* : Import only specific legacy IDs}
        {--from-id= : Start legacy ID}
        {--to-id= : End legacy ID}
        {--limit= : Max rows to import}
        {--default-user-email= : Fallback user email in local system}
        {--default-status=approved : Status to set on imported docs}
        {--dry-run : Validate and map only, no writes}
        {--queue : Dispatch chunk jobs to queue}
        {--update-existing : Update rows that were previously imported}
        {--skip-files : Do not download source files, store URL pointer file instead}
        {--redownload-files : Force re-download file on update existing}';

    protected $description = 'Import legacy WordPress wpDataTables documents into current DMS.';

    public function handle(WpDocumentImportService $importService): int
    {
        $chunkSize = max(1, (int) $this->option('chunk'));
        $legacyIds = $this->collectLegacyIds();

        if ($legacyIds === []) {
            $this->warn('No source rows matched the filter.');
            return self::SUCCESS;
        }

        $options = [
            'connection' => (string) $this->option('connection'),
            'source_table' => (string) $this->option('source-table'),
            'source_name' => (string) $this->option('source-name'),
            'default_user_email' => $this->option('default-user-email'),
            'default_status' => (string) $this->option('default-status'),
            'dry_run' => (bool) $this->option('dry-run'),
            'update_existing' => (bool) $this->option('update-existing'),
            'skip_files' => (bool) $this->option('skip-files'),
            'redownload_files' => (bool) $this->option('redownload-files'),
        ];

        $this->info(sprintf(
            'Preparing import: %d rows | chunk=%d | mode=%s',
            count($legacyIds),
            $chunkSize,
            $this->option('queue') ? 'queue' : 'sync'
        ));

        if ($this->option('dry-run')) {
            $this->warn('Dry-run enabled: no data will be written.');
        }

        $chunks = array_chunk($legacyIds, $chunkSize);

        if ((bool) $this->option('queue')) {
            foreach ($chunks as $index => $chunk) {
                ImportWpDocumentChunkJob::dispatch($chunk, $options);
                $this->line(sprintf('Dispatched chunk %d/%d (%d rows).', $index + 1, count($chunks), count($chunk)));
            }

            $this->newLine();
            $this->info('All chunks dispatched to queue.');
            return self::SUCCESS;
        }

        $aggregate = [
            'processed' => 0,
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'failures' => [],
        ];

        foreach ($chunks as $index => $chunk) {
            $this->line(sprintf('Processing chunk %d/%d (%d rows)...', $index + 1, count($chunks), count($chunk)));

            $summary = $importService->importLegacyIds($chunk, $options);

            $aggregate['processed'] += (int) $summary['processed'];
            $aggregate['imported'] += (int) $summary['imported'];
            $aggregate['updated'] += (int) $summary['updated'];
            $aggregate['skipped'] += (int) $summary['skipped'];
            $aggregate['failed'] += (int) $summary['failed'];
            $aggregate['failures'] = array_merge($aggregate['failures'], $summary['failures']);
        }

        $this->newLine();
        $this->info('Import finished.');
        $this->line("Processed: {$aggregate['processed']}");
        $this->line("Imported: {$aggregate['imported']}");
        $this->line("Updated: {$aggregate['updated']}");
        $this->line("Skipped: {$aggregate['skipped']}");
        $this->line("Failed: {$aggregate['failed']}");

        if ($aggregate['failed'] > 0) {
            $this->newLine();
            $this->warn('Failed rows (first 20):');
            foreach (array_slice($aggregate['failures'], 0, 20) as $failure) {
                $legacyId = $failure['legacy_id'] ?? '?';
                $message = $failure['message'] ?? 'Unknown error';
                $this->line("- wdt_ID {$legacyId}: {$message}");
            }
        }

        return $aggregate['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return array<int, int>
     */
    private function collectLegacyIds(): array
    {
        $connection = (string) $this->option('connection');
        $table = (string) $this->option('source-table');

        $query = DB::connection($connection)
            ->table($table)
            ->select('wdt_ID')
            ->orderBy('wdt_ID');

        $ids = array_values(array_filter(array_map(
            static fn ($value) => is_numeric($value) ? (int) $value : null,
            (array) $this->option('id')
        )));

        if ($ids !== []) {
            $query->whereIn('wdt_ID', $ids);
        } else {
            $fromId = $this->option('from-id');
            $toId = $this->option('to-id');
            $limit = $this->option('limit');

            if (is_numeric($fromId)) {
                $query->where('wdt_ID', '>=', (int) $fromId);
            }

            if (is_numeric($toId)) {
                $query->where('wdt_ID', '<=', (int) $toId);
            }

            if (is_numeric($limit) && (int) $limit > 0) {
                $query->limit((int) $limit);
            }
        }

        return $query->pluck('wdt_ID')
            ->map(static fn ($id) => (int) $id)
            ->filter(static fn ($id) => $id > 0)
            ->values()
            ->all();
    }
}
