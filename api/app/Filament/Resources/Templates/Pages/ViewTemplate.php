<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Pages;

use App\Filament\Resources\Templates\TemplateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTemplate extends ViewRecord
{
    protected static string $resource = TemplateResource::class;

    public function getTitle(): string
    {
        return 'Şablon məlumatları';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Redaktə et'),
        ];
    }
}
