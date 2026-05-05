<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Şablon təfərrüatları')
                    ->columns(2)
                    ->components([
                        ImageEntry::make('preview_path')
                            ->label('Önizləmə')
                            ->disk('public')
                            ->columnSpanFull(),

                        TextEntry::make('name')
                            ->label('Ad')
                            ->weight('bold'),

                        TextEntry::make('slug')
                            ->label('URL açarı')
                            ->copyable(),

                        TextEntry::make('category')
                            ->label('Kateqoriya')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'professional' => 'Peşəkar',
                                'modern' => 'Müasir',
                                'creative' => 'Yaradıcı',
                                'classic' => 'Klassik',
                                'student' => 'Tələbə',
                                default => $state,
                            }),

                        TextEntry::make('price_minor')
                            ->label('Qiymət')
                            ->formatStateUsing(fn ($state, $record): string => $state > 0
                                ? number_format($state / 100, 2, '.', ' ') . ' ' . ($record->currency ?? 'AZN')
                                : 'Pulsuz')
                            ->color(fn ($state): string => $state > 0 ? 'warning' : 'success')
                            ->badge(),

                        IconEntry::make('is_active')
                            ->label('Aktiv')
                            ->boolean(),

                        TextEntry::make('description')
                            ->label('Təsvir')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Vaxt damğaları')
                    ->columns(2)
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Yaradılma tarixi')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Son yenilənmə')
                            ->dateTime('d.m.Y H:i'),
                    ]),
            ]);
    }
}
