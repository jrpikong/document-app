<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RepairDocumentVisibilityCommand extends Command
{
    protected $signature = 'documents:repair-visibility
        {--disk=public : Filesystem disk}
        {--prefix=documents : Only process file_path with this prefix}
        {--dry-run : Show what would be changed without writing}';

    protected $description = 'Ensure existing document files are set to public visibility.';

    public function handle(): int
    {
        $diskName = (string) $this->option('disk');
        $prefix = trim((string) $this->option('prefix'));
        $dryRun = (bool) $this->option('dry-run');

        $disk = Storage::disk($diskName);

        $fixed = 0;
        $missing = 0;
        $skipped = 0;

        Document::query()
            ->select(['id', 'file_path'])
            ->whereNotNull('file_path')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($disk, $prefix, $dryRun, &$fixed, &$missing, &$skipped): void {
                foreach ($rows as $row) {
                    $path = (string) $row->file_path;
                    if ($path === '') {
                        $skipped++;
                        continue;
                    }

                    if ($prefix !== '' && ! str_starts_with($path, $prefix)) {
                        $skipped++;
                        continue;
                    }

                    if (! $disk->exists($path)) {
                        $missing++;
                        $this->warn("Missing: #{$row->id} {$path}");
                        continue;
                    }

                    if (! $dryRun) {
                        $disk->setVisibility($path, 'public');
                    }

                    $fixed++;
                }
            });

        $this->newLine();
        $this->info('Repair finished.');
        $this->line("Fixed: {$fixed}");
        $this->line("Missing: {$missing}");
        $this->line("Skipped: {$skipped}");

        if ($dryRun) {
            $this->warn('Dry-run enabled: no changes were written.');
        }

        return self::SUCCESS;
    }
}
