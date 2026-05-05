<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\CV\Enums\CvStatus;
use App\Domain\CV\Models\Cv;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestCvs extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Son yaradılan CV-lər')
            ->description('İstifadəçilər tərəfindən ən son yaradılmış CV-lər')
            ->query(
                Cv::query()
                    ->with(['user', 'template'])
                    ->latest()
                    ->limit(10)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->label('Başlıq')
                    ->weight('medium')
                    ->limit(40),

                TextColumn::make('user.name')
                    ->label('İstifadəçi')
                    ->icon('heroicon-o-user'),

                TextColumn::make('template.name')
                    ->label('Şablon')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

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
                    }),

                IconColumn::make('is_public')
                    ->label('İctimai')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Yaradılma')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->emptyStateHeading('Hələ heç bir CV yoxdur')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
