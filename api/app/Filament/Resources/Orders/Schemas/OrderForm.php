<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Domain\Billing\Enums\OrderStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sifariş haqqında')
                    ->columns(2)
                    ->components([
                        TextInput::make('reference')
                            ->label('Sifariş nömrəsi')
                            ->required()
                            ->maxLength(32)
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'ORD-' . strtoupper(Str::random(10))),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                OrderStatus::Pending->value => 'Gözləmədə',
                                OrderStatus::Paid->value => 'Ödənilib',
                                OrderStatus::Failed->value => 'Uğursuz',
                                OrderStatus::Refunded->value => 'Qaytarılıb',
                                OrderStatus::Cancelled->value => 'Ləğv edilib',
                            ])
                            ->default(OrderStatus::Pending->value)
                            ->required()
                            ->native(false),

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

                        Select::make('cv_id')
                            ->label('CV')
                            ->relationship('cv', 'title')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Ödəniş')
                    ->columns(3)
                    ->components([
                        TextInput::make('amount_minor')
                            ->label('Məbləğ (qəpik)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->helperText('Məsələn 499 = 4.99 AZN.'),

                        Select::make('currency')
                            ->label('Valyuta')
                            ->options([
                                'AZN' => 'AZN',
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                                'TRY' => 'TRY',
                            ])
                            ->default('AZN')
                            ->required()
                            ->native(false),

                        Select::make('payment_method')
                            ->label('Ödəniş üsulu')
                            ->options([
                                'stripe' => 'Stripe (kart)',
                                'paypal' => 'PayPal',
                                'cash' => 'Nağd',
                                'bank_transfer' => 'Bank köçürməsi',
                            ])
                            ->native(false),

                        DateTimePicker::make('paid_at')
                            ->label('Ödəniş tarixi')
                            ->seconds(false),
                    ]),

                Section::make('Əlavə məlumat')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        KeyValue::make('metadata')
                            ->label('Metaməlumat')
                            ->keyLabel('Açar')
                            ->valueLabel('Dəyər')
                            ->reorderable(),
                    ]),
            ]);
    }
}
