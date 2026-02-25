<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 10;

    protected ?string $heading = 'Operational Pulse';

    protected ?string $description = 'Live metrics for approval performance, backlog, and quality.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $last30Days = now()->subDays(30);
        $prev30Start = now()->subDays(60);

        $totalDocuments = Document::query()->count();
        $pendingCount = Document::query()->where('status', Document::STATUS_SUBMITTED)->count();
        $overSlaCount = Document::query()
            ->where('status', Document::STATUS_SUBMITTED)
            ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, NOW()) > sla_hours')
            ->count();
        $approvedToday = Document::query()
            ->where('status', Document::STATUS_APPROVED)
            ->whereDate('approved_at', '>=', $today)
            ->count();

        $approved30 = Document::query()
            ->where('status', Document::STATUS_APPROVED)
            ->whereDate('approved_at', '>=', $last30Days)
            ->count();
        $rejected30 = Document::query()
            ->where('status', Document::STATUS_REJECTED)
            ->whereDate('updated_at', '>=', $last30Days)
            ->count();
        $decisions30 = $approved30 + $rejected30;
        $approvalRate30 = $decisions30 > 0 ? round(($approved30 / $decisions30) * 100, 1) : 0.0;

        $approvedPrev30 = Document::query()
            ->where('status', Document::STATUS_APPROVED)
            ->whereBetween('approved_at', [$prev30Start, $last30Days])
            ->count();
        $rejectedPrev30 = Document::query()
            ->where('status', Document::STATUS_REJECTED)
            ->whereBetween('updated_at', [$prev30Start, $last30Days])
            ->count();
        $decisionsPrev30 = $approvedPrev30 + $rejectedPrev30;
        $approvalRatePrev30 = $decisionsPrev30 > 0 ? ($approvedPrev30 / $decisionsPrev30) * 100 : 0.0;
        $approvalRateDelta = round($approvalRate30 - $approvalRatePrev30, 1);

        $avgApprovalHours = Document::query()
            ->whereNotNull('approved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg'))
            ->value('avg');
        $avgApprovalHours = round((float) ($avgApprovalHours ?? 0), 1);

        $avgApprovalPrev = Document::query()
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [$prev30Start, $last30Days])
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg'))
            ->value('avg');
        $avgApprovalPrev = (float) ($avgApprovalPrev ?? 0);
        $avgApprovalDelta = round($avgApprovalHours - $avgApprovalPrev, 1);

        $pendingSparkline = $this->statusSparkline(Document::STATUS_SUBMITTED);
        $approvedSparkline = $this->statusSparkline(Document::STATUS_APPROVED, 'approved_at');
        $rejectedSparkline = $this->statusSparkline(Document::STATUS_REJECTED, 'updated_at');

        return [
            Stat::make('Total Documents', number_format($totalDocuments))
                ->icon('heroicon-o-document-text')
                ->description('All records in the repository')
                ->color('primary'),

            Stat::make('Pending Approvals', number_format($pendingCount))
                ->icon('heroicon-o-clock')
                ->description('Awaiting reviewer action')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->chart($pendingSparkline)
                ->color('warning'),

            Stat::make('Over SLA', number_format($overSlaCount))
                ->icon('heroicon-o-exclamation-triangle')
                ->description('Pending documents beyond SLA target')
                ->chart($pendingSparkline)
                ->color($overSlaCount > 0 ? 'danger' : 'success'),

            Stat::make('Approved Today', number_format($approvedToday))
                ->icon('heroicon-o-check-badge')
                ->description('Documents finalized since midnight')
                ->chart($approvedSparkline)
                ->color('success'),

            Stat::make('Approval Rate (30d)', $approvalRate30 . '%')
                ->icon('heroicon-o-chart-bar-square')
                ->description($this->formatPercentDelta($approvalRateDelta, 'vs previous 30d'))
                ->descriptionIcon($approvalRateDelta >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->chart($approvedSparkline)
                ->color($approvalRate30 >= 85 ? 'success' : ($approvalRate30 >= 70 ? 'warning' : 'danger')),

            Stat::make('Avg Approval Time', $avgApprovalHours . ' hrs')
                ->icon('heroicon-o-bolt')
                ->description($this->formatHourDelta($avgApprovalDelta))
                ->descriptionIcon($avgApprovalDelta <= 0 ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-arrow-trending-up')
                ->chart($rejectedSparkline)
                ->color($avgApprovalHours <= 24 ? 'success' : ($avgApprovalHours <= 48 ? 'warning' : 'danger')),
        ];
    }

    /**
     * @return array<float>
     */
    private function statusSparkline(string $status, string $dateColumn = 'created_at'): array
    {
        $days = collect(range(6, 0))
            ->map(fn (int $i): string => now()->subDays($i)->format('Y-m-d'));

        $raw = Document::query()
            ->where('status', $status)
            ->whereDate($dateColumn, '>=', now()->subDays(6))
            ->selectRaw("DATE({$dateColumn}) as d, COUNT(*) as total")
            ->groupBy('d')
            ->pluck('total', 'd');

        return $days
            ->map(fn (string $day): float => (float) ($raw[$day] ?? 0))
            ->all();
    }

    private function formatPercentDelta(float $delta, string $suffix): string
    {
        if ($delta > 0) {
            return '+' . $delta . '% ' . $suffix;
        }

        if ($delta < 0) {
            return $delta . '% ' . $suffix;
        }

        return 'No change vs previous period';
    }

    private function formatHourDelta(float $deltaHours): string
    {
        if ($deltaHours < 0) {
            return abs($deltaHours) . ' hrs faster vs previous 30d';
        }

        if ($deltaHours > 0) {
            return $deltaHours . ' hrs slower vs previous 30d';
        }

        return 'No change vs previous 30d';
    }
}
