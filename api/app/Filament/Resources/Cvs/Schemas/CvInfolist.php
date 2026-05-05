<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Schemas;

use App\Domain\CV\Enums\CvStatus;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CvInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('CV təfərrüatları')
                    ->columns(2)
                    ->components([
                        TextEntry::make('title')
                            ->label('Başlıq')
                            ->weight('bold'),

                        TextEntry::make('slug')
                            ->label('URL açarı')
                            ->copyable(),

                        TextEntry::make('user.name')
                            ->label('İstifadəçi')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('template.name')
                            ->label('Şablon')
                            ->icon('heroicon-o-rectangle-stack')
                            ->placeholder('Seçilməyib'),

                        TextEntry::make('status')
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

                        IconEntry::make('is_public')
                            ->label('İctimai')
                            ->boolean(),

                        TextEntry::make('published_at')
                            ->label('Dərc tarixi')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),
                    ]),

                Section::make('Vaxt damğaları')
                    ->columns(3)
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Yaradılma tarixi')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Son yenilənmə')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('deleted_at')
                            ->label('Silinmə tarixi')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),
                    ]),
            ]);
    }
}
