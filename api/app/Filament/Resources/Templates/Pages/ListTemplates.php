<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Pages;

use App\Filament\Resources\Templates\TemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    public function getTitle(): string
    {
        return 'Şablonlar';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Yeni şablon'),
        ];
    }
}
