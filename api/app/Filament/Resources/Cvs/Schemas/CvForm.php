<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Schemas;

use App\Domain\CV\Enums\CvStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CvForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Əsas məlumatlar')
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Başlıq')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('URL açarı')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Yalnız hərflər, rəqəmlər və tire istifadə edin.'),

                        Select::make('user_id')
                            ->label('İstifadəçi')
                            ->relationship('user', 'name')
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),

                        Select::make('template_id')
                            ->label('Şablon')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Status və yayım')
                    ->columns(3)
                    ->components([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                CvStatus::Draft->value => 'Qaralama',
                                CvStatus::Published->value => 'Dərc olunub',
                                CvStatus::Archived->value => 'Arxivləşib',
                            ])
                            ->default(CvStatus::Draft->value)
                            ->required()
                            ->native(false),

                        Toggle::make('is_public')
                            ->label('İctimai görünür')
                            ->helperText('Aktiv olduqda CV hər kəs üçün açıqdır.')
                            ->default(false)
                            ->inline(false),

                        DateTimePicker::make('published_at')
                            ->label('Dərc tarixi')
                            ->seconds(false),
                    ]),

                Section::make('Məzmun məlumatları')
                    ->description('CV-nin xam məzmunu. Açar/dəyər formatında dəyişiklik edə bilərsiniz.')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        KeyValue::make('data')
                            ->label('Məzmun')
                            ->keyLabel('Açar')
                            ->valueLabel('Dəyər')
                            ->reorderable(),
                    ]),
            ]);
    }
}
