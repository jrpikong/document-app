<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Models\Document;
use App\Services\DocumentWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('category.name')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => Document::STATUS_DRAFT,
                        'warning' => Document::STATUS_SUBMITTED,
                        'success' => Document::STATUS_APPROVED,
                        'danger' => Document::STATUS_REJECTED,
                    ])
                    ->icons([
                        'heroicon-o-pencil' => Document::STATUS_DRAFT,
                        'heroicon-o-clock' => Document::STATUS_SUBMITTED,
                        'heroicon-o-check-circle' => Document::STATUS_APPROVED,
                        'heroicon-o-x-circle' => Document::STATUS_REJECTED,
                    ]),

                TextColumn::make('uploader.name')
                    ->label('Uploaded By'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Document::STATUS_DRAFT => 'Draft',
                        Document::STATUS_SUBMITTED => 'Submitted',
                        Document::STATUS_APPROVED => 'Approved',
                        Document::STATUS_REJECTED => 'Rejected',
                    ]),
            ])

            ->recordActions([

                ViewAction::make(),

                /* ========== SUBMIT ========== */

                Action::make('submit')
                    ->label('Submit')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->visible(fn ($record) =>
                        $record->status === Document::STATUS_DRAFT &&
                        auth()->id() === $record->uploaded_by
                    )
                    ->action(function ($record) {
                        app(DocumentWorkflowService::class)
                            ->submit($record, auth()->user());

                        Notification::make()
                            ->title('Document submitted')
                            ->success()
                            ->send();
                    }),

                /* ========== APPROVE ========== */

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->authorize('approve')
                    ->visible(fn ($record) =>
                        $record->status === Document::STATUS_SUBMITTED
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(DocumentWorkflowService::class)
                            ->approve($record, auth()->user());

                        Notification::make()
                            ->title('Document approved')
                            ->success()
                            ->send();
                    }),

                /* ========== REJECT ========== */

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->authorize('approve')
                    ->visible(fn ($record) =>
                        $record->status === Document::STATUS_SUBMITTED
                    )
                    ->schema([
                        Textarea::make('note')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(4),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        app(DocumentWorkflowService::class)
                            ->reject($record, auth()->user(), $data['note']);

                        Notification::make()
                            ->title('Document rejected')
                            ->danger()
                            ->send();
                    }),


                DeleteAction::make(),

            ])

            ->defaultSort('created_at', 'desc');
    }

    /* ================= WORKFLOW ================= */

    protected static function submitDocument(Document $document): void
    {
        $document->update(['status' => Document::STATUS_SUBMITTED]);

        $document->histories()->create([
            'user_id' => auth()->id(),
            'from_status' => Document::STATUS_DRAFT,
            'to_status' => Document::STATUS_SUBMITTED,
        ]);

        Notification::make()
            ->title('Document submitted for approval')
            ->success()
            ->send();
    }

    protected static function approveDocument(Document $document): void
    {
        $document->update([
            'status' => Document::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $document->histories()->create([
            'user_id' => auth()->id(),
            'from_status' => Document::STATUS_SUBMITTED,
            'to_status' => Document::STATUS_APPROVED,
        ]);

        Notification::make()
            ->title('Document approved successfully')
            ->success()
            ->send();
    }

    protected static function rejectDocument(Document $document, string $note): void
    {
        $document->update([
            'status' => Document::STATUS_REJECTED,
            'approval_note' => $note,
        ]);

        $document->histories()->create([
            'user_id' => auth()->id(),
            'from_status' => Document::STATUS_SUBMITTED,
            'to_status' => Document::STATUS_REJECTED,
            'note' => $note,
        ]);

        Notification::make()
            ->title('Document rejected')
            ->danger()
            ->send();
    }
}
