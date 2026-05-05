<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Billing\Enums\OrderStatus;
use App\Domain\Billing\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Son sifarişlər')
            ->description('Ən son qeydə alınmış 10 sifariş')
            ->query(
                Order::query()
                    ->with(['user', 'template'])
                    ->latest()
                    ->limit(10)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('reference')
                    ->label('Sifariş №')
                    ->copyable()
                    ->weight('medium')
                    ->color('primary'),

                TextColumn::make('user.name')
                    ->label('İstifadəçi')
                    ->icon('heroicon-o-user'),

                TextColumn::make('template.name')
                    ->label('Şablon')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('amount_minor')
                    ->label('Məbləğ')
                    ->formatStateUsing(fn ($state, $record): string => number_format($state / 100, 2, '.', ' ') . ' ' . ($record->currency ?? 'AZN'))
                    ->weight('medium')
                    ->color('success')
                    ->alignEnd(),

                TextColumn::make('status')
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

                TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->emptyStateHeading('Hələ heç bir sifariş yoxdur')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }
}
