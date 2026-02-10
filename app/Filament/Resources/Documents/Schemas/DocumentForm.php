<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Document;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('File Upload')
                    ->schema([
                        FileUpload::make('file_path')
                            ->disk('public')
                            ->directory('documents')
                            ->preserveFilenames()
                            ->downloadable()
                            ->previewable()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/msword',
                                'image/*',
                            ])
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('original_name', basename($state));
                                }
                            })
                            ->required(),
                        Hidden::make('original_name'),
                        Hidden::make('uploaded_by')
                            ->default(fn () => Auth::id()),

                    ]),

                Section::make('Approval Notes')
                    ->schema([
                        Textarea::make('approval_note')
                            ->rows(3)
                            ->disabled(),
                    ])
                    ->visible(fn($record) => $record?->status === Document::STATUS_REJECTED),
            ]);
    }
}
