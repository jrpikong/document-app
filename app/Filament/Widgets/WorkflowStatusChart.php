<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;

class WorkflowStatusChart extends ChartWidget
{
    protected static ?int $sort = 30;

    protected ?string $heading = 'Workflow Composition';

    protected ?string $description = 'Current distribution by document status.';

    protected int | string | array $columnSpan = 4;

    protected function getData(): array
    {
        $counts = Document::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Documents',
                    'data' => [
                        (int) ($counts[Document::STATUS_DRAFT] ?? 0),
                        (int) ($counts[Document::STATUS_SUBMITTED] ?? 0),
                        (int) ($counts[Document::STATUS_APPROVED] ?? 0),
                        (int) ($counts[Document::STATUS_REJECTED] ?? 0),
                    ],
                    'backgroundColor' => [
                        '#9ca3af',
                        '#f59e0b',
                        '#22c55e',
                        '#ef4444',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Draft', 'Submitted', 'Approved', 'Rejected'],
        ];
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '68%',
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
