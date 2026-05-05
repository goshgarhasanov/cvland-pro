<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Sifarişi redaktə et';
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Sifariş yeniləndi';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Bax'),
            DeleteAction::make()->label('Sil'),
        ];
    }
}
