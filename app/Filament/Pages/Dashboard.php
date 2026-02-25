<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ApprovalTrendChart;
use App\Filament\Widgets\DashboardHeroWidget;
use App\Filament\Widgets\DocumentActivityWidget;
use App\Filament\Widgets\PendingByCategoryChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\WorkflowStatusChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string | null | \BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $title = 'Dashboard';

    /**
     * @return array<class-string>
     */
    protected function getHeaderWidgets(): array
    {
        return [
            DashboardHeroWidget::class,
        ];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return 12;
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            ApprovalTrendChart::class,
            WorkflowStatusChart::class,
            PendingByCategoryChart::class,
            DocumentActivityWidget::class,
        ];
    }
}
