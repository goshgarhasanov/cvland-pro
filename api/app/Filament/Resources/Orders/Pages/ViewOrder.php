<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Sifariş məlumatları';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Redaktə et'),
        ];
    }
}
