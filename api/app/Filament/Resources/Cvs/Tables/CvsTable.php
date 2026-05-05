<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Tables;

use App\Domain\CV\Enums\CvStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CvsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Başlıq')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->limit(40),

                TextColumn::make('user.name')
                    ->label('İstifadəçi')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),

                TextColumn::make('template.name')
                    ->label('Şablon')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (CvStatus $state): string => match ($state) {
                        CvStatus::Draft => 'Qaralama',
                        CvStatus::Published => 'Dərc olunub',
                        CvStatus::Archived => 'Arxivləşib',
                    })
                    ->color(fn (CvStatus $state): string => match ($state) {
                        CvStatus::Draft => 'gray',
                        CvStatus::Published => 'success',
                        CvStatus::Archived => 'warning',
                    })
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('İctimai')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Dərc tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Yaradılma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make()->label('Silinmişlər'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        CvStatus::Draft->value => 'Qaralama',
                        CvStatus::Published->value => 'Dərc olunub',
                        CvStatus::Archived->value => 'Arxivləşib',
                    ]),

                TernaryFilter::make('is_public')
                    ->label('İctimai görünən')
                    ->trueLabel('Yalnız ictimai')
                    ->falseLabel('Yalnız gizli')
                    ->placeholder('Hamısı'),

                SelectFilter::make('template_id')
                    ->label('Şablon')
                    ->relationship('template', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()->label('Bax'),
                EditAction::make()->label('Redaktə et'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Sil'),
                    ForceDeleteBulkAction::make()->label('Tam sil'),
                    RestoreBulkAction::make()->label('Bərpa et'),
                ]),
            ])
            ->emptyStateHeading('Hələ heç bir CV yoxdur')
            ->emptyStateDescription('İlk CV-ni yaratmaq üçün yuxarıdakı düyməni istifadə edin.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
