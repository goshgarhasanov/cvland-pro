<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Pages;

use App\Filament\Resources\Cvs\CvResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCv extends ViewRecord
{
    protected static string $resource = CvResource::class;

    public function getTitle(): string
    {
        return 'CV məlumatları';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Redaktə et'),
        ];
    }
}
