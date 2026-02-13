<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Document;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document Information')
                    ->description('Provide basic details about the document')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextInput::make('title')
                            ->label('Document Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Monthly Report January 2025')
                            ->helperText('Enter a descriptive title for easy identification')
                            ->autocomplete(false)
                            ->live(onBlur: true)
                            ->columnSpan(2),

                        Select::make('category_id')
                            ->label('Document Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select a category')
                            ->helperText('Choose the appropriate category for this document')
                            ->native(false)
                            ->suffixIcon('heroicon-o-folder')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Category Name'),
                            ])->columnSpanFull(),

                        TextEntry::make('uploader.name')
                            ->label('Uploaded By')
                            ->default('-')
                            ->icon('heroicon-o-user')
                            ->columnSpan(1)
                            ->visible(fn ($record) => $record !== null),

                        TextEntry::make('created_at')
                            ->label('Upload Date')
                            ->dateTime('d M Y, H:i')
                            ->default('-')
                            ->icon('heroicon-o-calendar')
                            ->columnSpan(1)
                            ->visible(fn ($record) => $record !== null),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('File Upload')
                    ->description('Upload your document file (PDF, Excel, Word, or Image)')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->schema([
                        Section::make('')
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('file_requirements')
                                    ->label('File Requirements')
                                    ->columnSpanFull()
                                    ->default(new HtmlString('
                                        <div class="text-sm space-y-2 p-4 bg-blue-50 dark:bg-blue-950 rounded-lg">
                                            <p class="font-semibold text-blue-900 dark:text-blue-100">📋 Accepted formats:</p>
                                            <ul class="list-disc list-inside space-y-1 text-blue-800 dark:text-blue-200">
                                                <li>PDF documents (.pdf)</li>
                                                <li>Excel files (.xlsx, .xls)</li>
                                                <li>Word documents (.doc, .docx)</li>
                                                <li>Images (.jpg, .png, .gif, .webp)</li>
                                            </ul>
                                            <p class="font-semibold text-blue-900 dark:text-blue-100 mt-3">⚠️ Important:</p>
                                            <ul class="list-disc list-inside space-y-1 text-blue-800 dark:text-blue-200">
                                                <li>Maximum file size: 10 MB</li>
                                                <li>Use descriptive filenames</li>
                                                <li>Ensure file is not corrupted</li>
                                            </ul>
                                        </div>
                                    ')),

                                FileUpload::make('file_path')
                                    ->label('Document File')
                                    ->disk('public')
                                    ->directory('documents')
                                    ->visibility('private')
                                    ->preserveFilenames()
                                    ->downloadable()
                                    ->openable()
                                    ->previewable(true)
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'application/vnd.ms-excel',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'image/jpeg',
                                        'image/png',
                                        'image/gif',
                                        'image/webp',
                                    ])
                                    ->maxSize(10240) // 10MB
                                    ->helperText('Drag & drop or click to upload. Max 10MB')
                                    ->hint(fn ($record) => $record?->original_name
                                        ? "Current: {$record->original_name}"
                                        : null
                                    )
                                    ->hintIcon('heroicon-o-information-circle')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $filename = is_string($state) ? basename($state) : $state->getClientOriginalName();
                                            $set('original_name', $filename);
                                        }
                                    })
                                    ->live()
                                    ->required()
                                    ->columnSpan(1),
                            ]),

                        Hidden::make('original_name'),
                        Hidden::make('uploaded_by')
                            ->default(fn () => Auth::id()),

                        TextEntry::make('original_name')
                            ->label('📄 Current Filename')
                            ->default('No file uploaded yet')
                            ->icon('heroicon-o-document')
                            ->copyable()
                            ->visible(fn ($record) => $record !== null && $record->file_path)
                            ->columnSpan(1),

                        TextEntry::make('file_size')
                            ->label('📦 File Size')
                            ->default('0 B')
                            ->formatStateUsing(fn ($state) => self::formatBytes((int) ($state ?? 0))) // Cast ke int di sini
                            ->icon('heroicon-o-archive-box')
                            ->visible(fn ($record) => $record !== null && $record->file_path)
                            ->columnSpan(1),

                        TextEntry::make('created_at')
                            ->label('📅 Upload Time')
                            ->default('-')
                            ->since()
                            ->icon('heroicon-o-clock')
                            ->visible(fn ($record) => $record !== null && $record->file_path)
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Document Status')
                    ->description('Review approval status and notes')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Current Status')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                Document::STATUS_SUBMITTED => 'warning',
                                Document::STATUS_APPROVED => 'success',
                                Document::STATUS_REJECTED => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn ($state) => match($state) {
                                Document::STATUS_SUBMITTED => 'heroicon-o-clock',
                                Document::STATUS_APPROVED => 'heroicon-o-check-circle',
                                Document::STATUS_REJECTED => 'heroicon-o-x-circle',
                                default => 'heroicon-o-question-mark-circle',
                            })
                            ->formatStateUsing(fn ($state) => match($state) {
                                Document::STATUS_SUBMITTED => 'Pending Approval',
                                Document::STATUS_APPROVED => 'Approved',
                                Document::STATUS_REJECTED => 'Rejected',
                                default => 'Unknown',
                            })
                            ->default('Pending Upload')
                            ->columnSpan(2),

                        TextEntry::make('approver.name')
                            ->label('Reviewed By')
                            ->default('Not yet reviewed')
                            ->icon('heroicon-o-user-circle')
                            ->visible(fn ($record) => $record?->approved_at || $record?->rejected_at)
                            ->columnSpan(1),

                        TextEntry::make('approved_at')
                            ->label('✅ Approved At')
                            ->dateTime('d M Y, H:i')
                            ->default('-')
                            ->icon('heroicon-o-check-badge')
                            ->color('success')
                            ->visible(fn ($record) => $record?->approved_at !== null)
                            ->columnSpan(1),

                        TextEntry::make('rejected_at')
                            ->label('❌ Rejected At')
                            ->dateTime('d M Y, H:i')
                            ->default('-')
                            ->icon('heroicon-o-x-circle')
                            ->color('danger')
                            ->visible(fn ($record) => $record?->rejected_at !== null)
                            ->columnSpan(1),

                        Textarea::make('approval_note')
                            ->label('Rejection Reason')
                            ->rows(4)
                            ->disabled()
                            ->helperText('This note was provided by the approver')
                            ->placeholder('No rejection note available')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record?->status === Document::STATUS_REJECTED),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(fn ($record) => $record?->status !== Document::STATUS_REJECTED)
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    private static function formatBytes($bytes, int $precision = 2): string
    {
        // Convert to integer, handle null/empty values
        $bytes = (int) ($bytes ?? 0);

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
