<?php

namespace App\Jobs;

use App\Services\WpDocumentImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ImportWpDocumentChunkJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param array<int, int> $legacyIds
     * @param array<string, mixed> $options
     */
    public function __construct(
        public array $legacyIds,
        public array $options
    ) {}

    public function handle(WpDocumentImportService $importService): void
    {
        $summary = $importService->importLegacyIds($this->legacyIds, $this->options);

        Log::info('WP document import chunk finished', [
            'chunk_size' => count($this->legacyIds),
            'summary' => $summary,
        ]);
    }
}
