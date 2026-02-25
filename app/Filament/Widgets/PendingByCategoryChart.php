<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;

class PendingByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 40;

    protected ?string $heading = 'Pending by Category';

    protected ?string $description = 'Backlog concentration by category (top 8).';

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $rows = Document::query()
            ->join('categories', 'categories.id', '=', 'documents.category_id')
            ->where('documents.status', Document::STATUS_SUBMITTED)
            ->selectRaw('categories.name as category_name, COUNT(documents.id) as total')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => $rows->pluck('total')->map(fn ($value): int => (int) $value)->all(),
                    'backgroundColor' => '#f59e0b',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $rows->pluck('category_name')->all(),
        ];
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
