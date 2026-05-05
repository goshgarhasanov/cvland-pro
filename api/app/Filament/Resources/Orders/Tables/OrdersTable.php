<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Tables;

use App\Domain\Billing\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->label('Sifariş №')
                    ->searchable()
                    ->copyable()
                    ->weight('medium')
                    ->color('primary'),

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

                TextColumn::make('amount_minor')
                    ->label('Məbləğ')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record): string => number_format($state / 100, 2, '.', ' ') . ' ' . ($record->currency ?? 'AZN'))
                    ->weight('medium')
                    ->color('success')
                    ->alignEnd(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
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

                TextColumn::make('payment_method')
                    ->label('Ödəniş üsulu')
                    ->badge()
                    ->placeholder('—')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'cash' => 'Nağd',
                        'bank_transfer' => 'Bank köçürməsi',
                        default => $state ?? '—',
                    })
                    ->toggleable(),

                TextColumn::make('paid_at')
                    ->label('Ödəniş tarixi')
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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        OrderStatus::Pending->value => 'Gözləmədə',
                        OrderStatus::Paid->value => 'Ödənilib',
                        OrderStatus::Failed->value => 'Uğursuz',
                        OrderStatus::Refunded->value => 'Qaytarılıb',
                        OrderStatus::Cancelled->value => 'Ləğv edilib',
                    ]),

                SelectFilter::make('currency')
                    ->label('Valyuta')
                    ->options([
                        'AZN' => 'AZN',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'TRY' => 'TRY',
                    ]),

                Filter::make('paid')
                    ->label('Yalnız ödənilmişlər')
                    ->query(fn (Builder $query): Builder => $query->where('status', OrderStatus::Paid->value)),
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
            ->emptyStateHeading('Hələ heç bir sifariş yoxdur')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }
}
