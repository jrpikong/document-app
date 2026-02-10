<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ApprovalSlaWidget;
use App\Filament\Widgets\ApprovalTrendChart;
use App\Filament\Widgets\OverSlaWidget;
use App\Filament\Widgets\PendingApprovalsWidget;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $title = 'Dashboard';
    public function getColumns(): int | array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            ApprovalTrendChart::class,
        ];
    }
}
