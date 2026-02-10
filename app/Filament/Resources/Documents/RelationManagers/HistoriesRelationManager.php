<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Document;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    protected static ?string $title = 'Approval History';

    protected static string|null|\BackedEnum $icon = 'heroicon-o-clock';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->searchable(false)
            ->columns([

                TextColumn::make('user.name')
                    ->label('Action By')
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('from_status')
                    ->label('From')
                    ->badge()
                    ->colors([
                        'gray' => Document::STATUS_DRAFT,
                        'warning' => Document::STATUS_SUBMITTED,
                        'success' => Document::STATUS_APPROVED,
                        'danger' => Document::STATUS_REJECTED,
                    ]),

                TextColumn::make('to_status')
                    ->label('To')
                    ->badge()
                    ->colors([
                        'gray' => Document::STATUS_DRAFT,
                        'warning' => Document::STATUS_SUBMITTED,
                        'success' => Document::STATUS_APPROVED,
                        'danger' => Document::STATUS_REJECTED,
                    ]),

                TextColumn::make('note')
                    ->label('Note')
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
