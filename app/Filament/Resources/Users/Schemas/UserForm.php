<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\DateTimePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) =>
                            filled($state) ? bcrypt($state) : null
                            )
                            ->required(fn ($record) => $record === null)
                            ->hidden(fn ($record) => $record !== null),
                    ])
                    ->columns(2),

                Section::make('Roles & Permissions')
                    ->schema([

                        /* ===== OPTION 1: SELECT MULTI ===== */

                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Assign Roles (Dropdown)'),


                        /* ===== OPTION 2: CHECKBOX LIST ===== */

                        CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->columns(2)
                            ->label('Assign Roles (Checkbox)'),

                    ]),
            ]);
    }
}
