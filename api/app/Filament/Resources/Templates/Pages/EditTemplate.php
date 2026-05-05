<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Pages;

use App\Filament\Resources\Templates\TemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTemplate extends EditRecord
{
    protected static string $resource = TemplateResource::class;

    public function getTitle(): string
    {
        return 'Şablonu redaktə et';
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Şablon yeniləndi';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Bax'),
            DeleteAction::make()->label('Sil'),
        ];
    }
}
