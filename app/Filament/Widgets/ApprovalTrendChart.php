<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;

class ApprovalTrendChart extends ChartWidget
{
    protected static ?int $sort=5;
    protected ?string $heading = 'Approvals (Last 7 Days)';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(6, 0))
            ->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

        $raw = Document::where('status', Document::STATUS_APPROVED)
            ->whereDate('approved_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(approved_at) as d, COUNT(*) total')
            ->groupBy('d')
            ->pluck('total', 'd');

        return [
            'datasets' => [
                [
                    'label' => 'Approved',
                    'data' => $days->map(fn ($d) => $raw[$d] ?? 0),
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
