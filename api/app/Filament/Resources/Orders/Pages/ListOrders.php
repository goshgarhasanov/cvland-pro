<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Sifarişlər';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Yeni sifariş'),
        ];
    }
}
