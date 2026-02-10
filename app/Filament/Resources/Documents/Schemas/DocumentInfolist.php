<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Document;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ================= DOCUMENT ================= */

            Section::make('Document Information')
                ->columns(2)
                ->components([

                    TextEntry::make('title')
                        ->weight('bold'),

                    TextEntry::make('category.name')
                        ->label('Category')
                        ->badge(),

                    TextEntry::make('status')
                        ->badge()
                        ->icons([
                            'heroicon-o-pencil' => Document::STATUS_DRAFT,
                            'heroicon-o-clock' => Document::STATUS_SUBMITTED,
                            'heroicon-o-check-circle' => Document::STATUS_APPROVED,
                            'heroicon-o-x-circle' => Document::STATUS_REJECTED,
                        ])
                        ->colors([
                            'gray' => Document::STATUS_DRAFT,
                            'warning' => Document::STATUS_SUBMITTED,
                            'success' => Document::STATUS_APPROVED,
                            'danger' => Document::STATUS_REJECTED,
                        ]),
                ]),

            /* ================= FILE ================= */

            Section::make('File')
                ->columns(2)
                ->components([

                    TextEntry::make('original_name')
                        ->label('File Name')
                        ->icon('heroicon-o-document-text'),

                    TextEntry::make('file_path')
                        ->label('Storage Path')
                        ->color('gray'),

                    TextEntry::make('file_path')
                        ->label('Download')
                        ->formatStateUsing(fn ($state) => 'Download File')
                        ->url(fn ($record) => Storage::url($record->file_path))
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-arrow-down-tray'),
                ]),

            /* ================= WORKFLOW ================= */

            Section::make('Approval Workflow')
                ->columns(2)
                ->components([

                    TextEntry::make('uploader.name')
                        ->label('Uploaded By'),

                    TextEntry::make('approver.name')
                        ->label('Approved By')
                        ->placeholder('Not approved'),

                    TextEntry::make('approved_at')
                        ->dateTime()
                        ->placeholder('—'),

                    TextEntry::make('approval_note')
                        ->columnSpanFull()
                        ->placeholder('No notes'),
                ]),

            /* ================= SYSTEM ================= */

            Section::make('System Info')
                ->columns(2)
                ->collapsed()
                ->components([

                    TextEntry::make('created_at')
                        ->dateTime(),

                    TextEntry::make('updated_at')
                        ->dateTime(),
                ]),

            Section::make('Preview')
                ->columnSpanFull()
                ->components([
                    ViewEntry::make('preview')
                        ->view('document-preview'),
                ]),
        ]);
    }
}
