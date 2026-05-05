<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cvs\Pages;

use App\Filament\Resources\Cvs\CvResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCv extends EditRecord
{
    protected static string $resource = CvResource::class;

    public function getTitle(): string
    {
        return 'CV-ni redaktə et';
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'CV yeniləndi';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Bax'),
            DeleteAction::make()->label('Sil'),
            ForceDeleteAction::make()->label('Tam sil'),
            RestoreAction::make()->label('Bərpa et'),
        ];
    }
}
