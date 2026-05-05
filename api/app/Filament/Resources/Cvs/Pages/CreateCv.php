<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Pages;

use App\Filament\Resources\Cvs\CvResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCv extends CreateRecord
{
    protected static string $resource = CvResource::class;

    public function getTitle(): string
    {
        return 'Yeni CV yarat';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'CV yaradıldı';
    }
}
