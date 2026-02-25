<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ApprovalTrendChart extends ChartWidget
{
    protected static ?int $sort = 20;

    protected ?string $heading = 'Workflow Throughput';

    protected ?string $description = 'Submission and decision trend over time.';

    protected int | string | array $columnSpan = 8;

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '14' => 'Last 14 days',
            '30' => 'Last 30 days',
        ];
    }

    protected function getData(): array
    {
        $daysCount = (int) ($this->filter ?: 14);
        $startDate = now()->subDays($daysCount - 1);

        $days = collect(range($daysCount - 1, 0))
            ->map(fn (int $i): string => now()->subDays($i)->format('Y-m-d'));

        $submitted = Document::query()
            ->whereDate('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as d, COUNT(*) as total')
            ->groupBy('d')
            ->pluck('total', 'd');

        $approved = Document::query()
            ->where('status', Document::STATUS_APPROVED)
            ->whereDate('approved_at', '>=', $startDate)
            ->selectRaw('DATE(approved_at) as d, COUNT(*) as total')
            ->groupBy('d')
            ->pluck('total', 'd');

        $rejected = Document::query()
            ->where('status', Document::STATUS_REJECTED)
            ->whereDate('updated_at', '>=', $startDate)
            ->selectRaw('DATE(updated_at) as d, COUNT(*) as total')
            ->groupBy('d')
            ->pluck('total', 'd');

        return [
            'datasets' => [
                [
                    'label' => 'Submitted',
                    'data' => $days->map(fn (string $day): int => (int) ($submitted[$day] ?? 0))->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'tension' => 0.35,
                    'fill' => true,
                ],
                [
                    'label' => 'Approved',
                    'data' => $days->map(fn (string $day): int => (int) ($approved[$day] ?? 0))->all(),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.15)',
                    'tension' => 0.35,
                    'fill' => true,
                ],
                [
                    'label' => 'Rejected',
                    'data' => $days->map(fn (string $day): int => (int) ($rejected[$day] ?? 0))->all(),
                    'borderColor' => '#dc2626',
                    'backgroundColor' => 'rgba(220, 38, 38, 0.12)',
                    'tension' => 0.35,
                    'fill' => true,
                ],
            ],
            'labels' => $days->map(fn (string $day): string => Carbon::parse($day)->format('d M'))->all(),
        ];
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
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
        return 'line';
    }
}
