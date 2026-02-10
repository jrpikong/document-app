<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $avgHours = Document::whereNotNull('approved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg'))
            ->value('avg');

        return [
            Stat::make('Avg Approval Time', round($avgHours ?? 0, 1) . ' hrs')
                ->icon('heroicon-o-bolt')
                ->color('success'),
            Stat::make(
                'Over SLA',
                Document::where('status', Document::STATUS_SUBMITTED)
                    ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, NOW()) > sla_hours')
                    ->count()
            )
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
            Stat::make(
                'Pending Approvals',
                Document::where('status', Document::STATUS_SUBMITTED)->count()
            )
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make(
                'Rejected',
                Document::where('status', Document::STATUS_REJECTED)->count()
            )
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->description('Documents rejected'),
        ];
    }
}
