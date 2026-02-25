<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\Widget;

class DashboardHeroWidget extends Widget
{
    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.dashboard-hero-widget';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $total = Document::query()->count();
        $approved = Document::query()->where('status', Document::STATUS_APPROVED)->count();
        $pending = Document::query()->where('status', Document::STATUS_SUBMITTED)->count();
        $rejected = Document::query()->where('status', Document::STATUS_REJECTED)->count();
        $approvalRate = ($approved + $rejected) > 0
            ? round(($approved / ($approved + $rejected)) * 100, 1)
            : 0;

        return [
            'today' => now()->format('d M Y'),
            'totals' => [
                'total' => $total,
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
                'approval_rate' => $approvalRate,
            ],
        ];
    }
}
