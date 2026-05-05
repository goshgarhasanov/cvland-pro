<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=6366f1&color=fff'),

                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('E-poçt')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('locale')
                    ->label('Dil')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'az' => 'AZ',
                        'en' => 'EN',
                        'tr' => 'TR',
                        'ru' => 'RU',
                        default => strtoupper($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'az' => 'success',
                        'en' => 'info',
                        'tr' => 'warning',
                        'ru' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                IconColumn::make('email_verified_at')
                    ->label('Təsdiqlənib')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('cvs_count')
                    ->label('CV sayı')
                    ->counts('cvs')
                    ->badge()
                    ->color('primary')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Qeydiyyat tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('Dil')
                    ->options([
                        'az' => 'Azərbaycanca',
                        'en' => 'İngiliscə',
                        'tr' => 'Türkcə',
                        'ru' => 'Rusca',
                    ]),

                Filter::make('verified')
                    ->label('Yalnız təsdiqlənmişlər')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            ])
            ->recordActions([
                ViewAction::make()->label('Bax'),
                EditAction::make()->label('Redaktə et'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Sil'),
                ]),
            ])
            ->emptyStateHeading('Hələ heç bir istifadəçi yoxdur')
            ->emptyStateDescription('Yeni istifadəçi yaratmaq üçün yuxarıdakı düyməni istifadə edin.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
