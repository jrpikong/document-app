<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Documents\DocumentResource;
use App\Models\Document;
use Filament\Widgets\Widget;

class DocumentActivityWidget extends Widget
{
    protected static ?int $sort = 50;

    protected string $view = 'filament.widgets.document-activity-widget';

    protected int | string | array $columnSpan = 6;

    protected function getViewData(): array
    {
        $recentDocuments = Document::query()
            ->with(['category:id,name', 'uploader:id,name'])
            ->latest()
            ->limit(7)
            ->get()
            ->map(fn (Document $document): array => [
                'id' => $document->id,
                'title' => $document->title,
                'status' => $document->status,
                'category' => $document->category?->name ?? '-',
                'uploader' => $document->uploader?->name ?? '-',
                'created_at' => $document->created_at,
                'url' => DocumentResource::getUrl('view', ['record' => $document]),
            ]);

        $overdueDocuments = Document::query()
            ->with(['category:id,name'])
            ->where('status', Document::STATUS_SUBMITTED)
            ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, NOW()) > sla_hours')
            ->orderBy('created_at')
            ->limit(7)
            ->get()
            ->map(function (Document $document): array {
                $hoursOpen = (int) $document->created_at->diffInHours(now());
                $hoursOverSla = max(0, $hoursOpen - (int) ($document->sla_hours ?? 24));

                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'category' => $document->category?->name ?? '-',
                    'hours_over_sla' => $hoursOverSla,
                    'created_at' => $document->created_at,
                    'url' => DocumentResource::getUrl('view', ['record' => $document]),
                ];
            });

        return [
            'recentDocuments' => $recentDocuments,
            'overdueDocuments' => $overdueDocuments,
            'statusLabels' => $this->statusLabels(),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            Document::STATUS_DRAFT => 'Draft',
            Document::STATUS_SUBMITTED => 'Submitted',
            Document::STATUS_APPROVED => 'Approved',
            Document::STATUS_REJECTED => 'Rejected',
        ];
    }
}
