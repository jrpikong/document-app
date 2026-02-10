<?php

namespace App\Filament\Resources\Users\Tables;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email)
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(',')
                    ->colors([
                        'danger' => fn ($state): bool => in_array('super_admin', (array) $state),
                        'warning' => fn ($state): bool => collect((array) $state)->contains(fn ($role) => str_contains($role, 'approver')),
                        'success' => fn ($state): bool => collect((array) $state)->contains(fn ($role) => str_contains($role, 'uploader')),
                        'primary' => fn ($state): bool => collect((array) $state)->contains(fn ($role) => str_contains($role, 'viewer')),
                        'gray' => fn ($state): bool => in_array('panel_user', (array) $state),
                    ])
                    ->searchable()
                    ->wrap(),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->tooltip(fn ($record) => $record->email_verified_at
                        ? 'Verified at ' . $record->email_verified_at->format('d M Y H:i')
                        : 'Not verified'
                    ),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter by Role'),

                TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->placeholder('All users')
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('email_verified_at'),
                        false: fn ($query) => $query->whereNull('email_verified_at'),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset User Password')
                    ->modalDescription(fn ($record) => "Reset password for {$record->name}?")
                    ->modalSubmitActionLabel('Reset Password')
                    ->action(function ($record) {
                        $newPassword = 'Password@' . date('Y');
                        $record->update([
                            'password' => Hash::make($newPassword),
                        ]);

                        Notification::make()
                            ->title('Password Reset Successfully')
                            ->body("New password: {$newPassword}")
                            ->success()
                            ->seconds(10)
                            ->send();
                    })
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),

                Action::make('verify_email')
                    ->label('Verify Email')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['email_verified_at' => now()]);

                        Notification::make()
                            ->title('Email Verified')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => is_null($record->email_verified_at) && auth()->user()->hasRole('super_admin')),
            ])
            ->headerActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Users')
                        ->modalDescription('Are you sure you want to delete selected users? This action cannot be undone.')
                        ->visible(fn () => auth()->user()->hasRole('super_admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Create your first user to get started.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
