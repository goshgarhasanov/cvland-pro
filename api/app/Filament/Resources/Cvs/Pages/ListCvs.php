<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Pages;

use App\Filament\Resources\Cvs\CvResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvs extends ListRecords
{
    protected static string $resource = CvResource::class;

    public function getTitle(): string
    {
        return 'CV-lər';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Yeni CV'),
        ];
    }
}
