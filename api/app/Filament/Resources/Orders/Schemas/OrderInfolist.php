<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Domain\Billing\Enums\OrderStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sifariş təfərrüatları')
                    ->columns(2)
                    ->components([
                        TextEntry::make('reference')
                            ->label('Sifariş nömrəsi')
                            ->copyable()
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (OrderStatus $state): string => match ($state) {
                                OrderStatus::Pending => 'Gözləmədə',
                                OrderStatus::Paid => 'Ödənilib',
                                OrderStatus::Failed => 'Uğursuz',
                                OrderStatus::Refunded => 'Qaytarılıb',
                                OrderStatus::Cancelled => 'Ləğv edilib',
                            })
                            ->color(fn (OrderStatus $state): string => match ($state) {
                                OrderStatus::Pending => 'warning',
                                OrderStatus::Paid => 'success',
                                OrderStatus::Failed => 'danger',
                                OrderStatus::Refunded => 'info',
                                OrderStatus::Cancelled => 'gray',
                            }),

                        TextEntry::make('user.name')
                            ->label('İstifadəçi')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('user.email')
                            ->label('E-poçt')
                            ->copyable(),

                        TextEntry::make('template.name')
                            ->label('Şablon')
                            ->placeholder('—'),

                        TextEntry::make('cv.title')
                            ->label('CV')
                            ->placeholder('—'),
                    ]),

                Section::make('Ödəniş məlumatları')
                    ->columns(3)
                    ->components([
                        TextEntry::make('amount_minor')
                            ->label('Məbləğ')
                            ->formatStateUsing(fn ($state, $record): string => number_format($state / 100, 2, '.', ' ') . ' ' . ($record->currency ?? 'AZN'))
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('payment_method')
                            ->label('Ödəniş üsulu')
                            ->badge()
                            ->placeholder('—')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                                'cash' => 'Nağd',
                                'bank_transfer' => 'Bank köçürməsi',
                                default => $state ?? '—',
                            }),

                        TextEntry::make('paid_at')
                            ->label('Ödəniş tarixi')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('Hələ ödənilməyib'),
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
