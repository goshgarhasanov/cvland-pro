<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil')
                    ->columns(2)
                    ->components([
                        ImageEntry::make('avatar_path')
                            ->label('Avatar')
                            ->circular()
                            ->disk('public')
                            ->columnSpanFull(),

                        TextEntry::make('name')
                            ->label('Ad Soyad')
                            ->weight('bold'),

                        TextEntry::make('email')
                            ->label('E-poçt')
                            ->copyable()
                            ->icon('heroicon-o-envelope'),

                        TextEntry::make('locale')
                            ->label('Dil')
                            ->badge(),

                        TextEntry::make('email_verified_at')
                            ->label('E-poçt təsdiqləndi')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('Təsdiqlənməyib'),
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
