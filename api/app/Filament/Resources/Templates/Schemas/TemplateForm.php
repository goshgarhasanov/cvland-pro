<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Şablon haqqında')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Ad')
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
                            ->unique(ignoreRecord: true),

                        Select::make('category')
                            ->label('Kateqoriya')
                            ->options([
                                'professional' => 'Peşəkar',
                                'modern' => 'Müasir',
                                'creative' => 'Yaradıcı',
                                'classic' => 'Klassik',
                                'student' => 'Tələbə',
                            ])
                            ->required()
                            ->native(false),

                        Toggle::make('is_active')
                            ->label('Aktiv')
                            ->helperText('Passiv şablonlar istifadəçilərə göstərilmir.')
                            ->default(true)
                            ->inline(false),

                        Textarea::make('description')
                            ->label('Təsvir')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Qiymətləndirmə')
                    ->columns(2)
                    ->components([
                        TextInput::make('price_minor')
                            ->label('Qiymət (qəpik)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Pulsuz şablon üçün 0 daxil edin. Məsələn 499 = 4.99 AZN.'),

                        Select::make('currency')
                            ->label('Valyuta')
                            ->options([
                                'AZN' => 'AZN — Azərbaycan manatı',
                                'USD' => 'USD — ABŞ dolları',
                                'EUR' => 'EUR — Avro',
                                'TRY' => 'TRY — Türk lirəsi',
                            ])
                            ->default('AZN')
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Şəkil və metaməlumat')
                    ->columns(2)
                    ->components([
                        FileUpload::make('preview_path')
                            ->label('Önizləmə şəkli')
                            ->image()
                            ->imageEditor()
                            ->directory('templates')
                            ->visibility('public'),

                        KeyValue::make('metadata')
                            ->label('Əlavə metaməlumat')
                            ->keyLabel('Açar')
                            ->valueLabel('Dəyər')
                            ->reorderable(),
                    ]),
            ]);
    }
}
