<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use App\Models\Document;
use App\Services\DocumentWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('submit')
                ->label('Submit')
                ->icon('heroicon-o-paper-airplane')
                ->color('warning')
                ->visible(fn ($record) =>
                    $record->status === Document::STATUS_DRAFT &&
                    auth()->id() === $record->uploaded_by
                )
                ->authorize('update')
                ->action(function ($record) {
                    app(DocumentWorkflowService::class)
                        ->submit($record, auth()->user());

                    Notification::make()
                        ->title('Document submitted')
                        ->success()
                        ->send();
                }),
        ];
    }
}
