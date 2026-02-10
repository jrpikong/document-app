<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->description('Basic user account details')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., John Doe')
                            ->autocomplete('name')
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('user@example.com')
                            ->autocomplete('email')
                            ->suffixIcon('heroicon-o-envelope')
                            ->columnSpan(1),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) =>
                            filled($state) ? bcrypt($state) : null
                            )
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn ($record) => $record === null)
                            ->maxLength(255)
                            ->placeholder(fn ($record) => $record === null ? 'Enter password' : 'Leave blank to keep current')
                            ->revealable()
                            ->autocomplete('new-password')
                            ->helperText(fn ($record) => $record !== null
                                ? 'Leave blank to keep current password'
                                : 'Minimum 8 characters recommended'
                            )
                            ->minLength(8)
                            ->columnSpan(1),

                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->dehydrated(false)
                            ->requiredWith('password')
                            ->same('password')
                            ->placeholder('Re-enter password')
                            ->revealable()
                            ->autocomplete('new-password')
                            ->visible(fn ($record) => $record === null)
                            ->columnSpan(1),

                        Toggle::make('email_verified')
                            ->label('Email Verified')
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle')
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Mark this email as verified')
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $set('email_verified_at', now());
                                } else {
                                    $set('email_verified_at', null);
                                }
                            })
                            ->dehydrated(false)
                            ->visible(fn () => auth()->user()->hasRole('super_admin'))
                            ->columnSpan(2),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->hidden()
                            ->dehydrated(true),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Roles & Permissions')
                    ->description('Assign roles to determine user access levels')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        KeyValueEntry::make('role_descriptions')
                            ->label('Role Reference Guide')
                            ->keyLabel('Role Name')
                            ->valueLabel('Access Level')
                            ->default(fn () => collect([
                                'super_admin' => '🔴 Full system access - can manage everything',
                                'approver_all_documents' => '🟠 Can approve and view all documents',
                                'approver_lab_lspro' => '🟠 Can approve Lab & LSPRO documents',
                                'uploader_lab_lspro' => '🟢 Can upload and view Lab & LSPRO documents',
                                'uploader_lab' => '🟢 Can upload and view Lab documents only',
                                'viewer_lab' => '🔵 Can only view Lab documents',
                                'panel_user' => '⚪ Basic panel access',
                            ])->all())
                            ->columnSpanFull(),

                        Select::make('roles')
                            ->label('Assign Roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->placeholder('Select one or more roles')
                            ->helperText('Select appropriate roles for this user')
                            ->options(function () {
                                // Format role names untuk display
                                return \Spatie\Permission\Models\Role::pluck('name', 'id')
                                    ->mapWithKeys(fn ($name, $id) => [
                                        $id => ucwords(str_replace('_', ' ', $name))
                                    ]);
                            })
                            ->native(false)
                            ->suffixIcon('heroicon-o-user-group')
                            ->columnSpanFull(),

                        // Alternative: Checkbox List (uncomment jika lebih suka tampilan checkbox)
                        /*
                        CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->columns(2)
                            ->label('Assign Roles (Checkbox)')
                            ->options(function () {
                                return \Spatie\Permission\Models\Role::pluck('name', 'id')
                                    ->mapWithKeys(fn ($name, $id) => [
                                        $id => ucwords(str_replace('_', ' ', $name))
                                    ]);
                            })
                            ->descriptions([
                                'super_admin' => 'Full system access',
                                'approver_all_documents' => 'Approve & view all docs',
                                'approver_lab_lspro' => 'Approve Lab & LSPRO',
                                'uploader_lab_lspro' => 'Upload Lab & LSPRO',
                                'uploader_lab' => 'Upload Lab only',
                                'viewer_lab' => 'View Lab only',
                            ])
                            ->columnSpanFull(),
                        */
                    ])
                    ->collapsible(),

                Section::make('Metadata')
                    ->description('System generated information')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->placeholder(fn ($record) => $record?->created_at?->format('d F Y, H:i') ?? '-'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->placeholder(fn ($record) => $record?->updated_at?->format('d F Y, H:i') ?? '-'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn ($record) => $record !== null),
            ]);
    }
}
