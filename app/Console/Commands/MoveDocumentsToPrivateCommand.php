<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveDocumentsToPrivateCommand extends Command
{
    protected $signature = 'documents:move-to-private
        {--from=public : Source disk}
        {--to=local : Target private disk}
        {--prefix=documents : Only process path starting with this prefix}
        {--dry-run : Preview only}
        {--delete-source : Delete source file after successful copy}
        {--overwrite : Overwrite target if exists}';

    protected $description = 'Copy existing document files from public disk to private disk without re-import.';

    public function handle(): int
    {
        $fromDiskName = (string) $this->option('from');
        $toDiskName = (string) $this->option('to');
        $prefix = trim((string) $this->option('prefix'));
        $dryRun = (bool) $this->option('dry-run');
        $deleteSource = (bool) $this->option('delete-source');
        $overwrite = (bool) $this->option('overwrite');

        $fromDisk = Storage::disk($fromDiskName);
        $toDisk = Storage::disk($toDiskName);

        $summary = [
            'processed' => 0,
            'copied' => 0,
            'already_in_target' => 0,
            'missing_in_source' => 0,
            'deleted_source' => 0,
            'failed' => 0,
        ];

        Document::query()
            ->select(['id', 'file_path'])
            ->whereNotNull('file_path')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (
                $fromDisk,
                $toDisk,
                $prefix,
                $dryRun,
                $deleteSource,
                $overwrite,
                &$summary
            ): void {
                foreach ($rows as $row) {
                    $path = trim((string) $row->file_path);
                    if ($path === '') {
                        continue;
                    }

                    if ($prefix !== '' && ! str_starts_with($path, $prefix)) {
                        continue;
                    }

                    $summary['processed']++;

                    $sourceExists = $fromDisk->exists($path);
                    $targetExists = $toDisk->exists($path);

                    if (! $sourceExists && $targetExists) {
                        $summary['already_in_target']++;
                        continue;
                    }

                    if (! $sourceExists && ! $targetExists) {
                        $summary['missing_in_source']++;
                        $this->warn("Missing both disks: #{$row->id} {$path}");
                        continue;
                    }

                    if ($targetExists && ! $overwrite) {
                        $summary['already_in_target']++;
                        continue;
                    }

                    if ($dryRun) {
                        $summary['copied']++;
                        if ($deleteSource) {
                            $summary['deleted_source']++;
                        }
                        continue;
                    }

                    try {
                        $stream = $fromDisk->readStream($path);
                        if (! is_resource($stream)) {
                            throw new \RuntimeException('Unable to open source stream.');
                        }

                        $ok = $toDisk->writeStream($path, $stream);
                        if (is_resource($stream)) {
                            fclose($stream);
                        }

                        if (! $ok || ! $toDisk->exists($path)) {
                            throw new \RuntimeException('Failed to write target file.');
                        }

                        $summary['copied']++;

                        if ($deleteSource) {
                            $fromDisk->delete($path);
                            $summary['deleted_source']++;
                        }
                    } catch (\Throwable $e) {
                        $summary['failed']++;
                        $this->error("Failed #{$row->id} {$path}: {$e->getMessage()}");
                    }
                }
            });

        $this->newLine();
        $this->info('Move to private finished.');
        $this->line('Processed: ' . $summary['processed']);
        $this->line('Copied: ' . $summary['copied']);
        $this->line('Already in target: ' . $summary['already_in_target']);
        $this->line('Missing in source: ' . $summary['missing_in_source']);
        $this->line('Deleted source: ' . $summary['deleted_source']);
        $this->line('Failed: ' . $summary['failed']);

        if ($dryRun) {
            $this->warn('Dry-run enabled: no file operation executed.');
        }

        if (! $deleteSource) {
            $this->warn('Source files were kept. Use --delete-source when you are ready.');
        }

        return $summary['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
