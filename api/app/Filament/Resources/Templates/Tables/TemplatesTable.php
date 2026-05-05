<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('preview_path')
                    ->label('Önizləmə')
                    ->disk('public')
                    ->square()
                    ->size(50)
                    ->defaultImageUrl(fn () => 'https://placehold.co/50x50/6366f1/ffffff?text=CV'),

                TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('slug')
                    ->label('URL açarı')
                    ->searchable()
                    ->toggleable()
                    ->color('gray'),

                TextColumn::make('category')
                    ->label('Kateqoriya')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'professional' => 'Peşəkar',
                        'modern' => 'Müasir',
                        'creative' => 'Yaradıcı',
                        'classic' => 'Klassik',
                        'student' => 'Tələbə',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'professional' => 'primary',
                        'modern' => 'info',
                        'creative' => 'warning',
                        'classic' => 'gray',
                        'student' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('price_minor')
                    ->label('Qiymət')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn ($state, $record): string => $state > 0
                        ? number_format($state / 100, 2, '.', ' ') . ' ' . ($record->currency ?? 'AZN')
                        : 'Pulsuz')
                    ->color(fn ($state): string => $state > 0 ? 'warning' : 'success'),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('cvs_count')
                    ->label('İstifadə')
                    ->counts('cvs')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Yaradılma')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kateqoriya')
                    ->options([
                        'professional' => 'Peşəkar',
                        'modern' => 'Müasir',
                        'creative' => 'Yaradıcı',
                        'classic' => 'Klassik',
                        'student' => 'Tələbə',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Aktiv')
                    ->trueLabel('Yalnız aktivlər')
                    ->falseLabel('Yalnız passivlər')
                    ->placeholder('Hamısı'),
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
            ->emptyStateHeading('Hələ heç bir şablon yoxdur')
            ->emptyStateIcon('heroicon-o-rectangle-stack');
    }
}
