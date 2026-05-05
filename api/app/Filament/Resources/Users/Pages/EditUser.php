<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'İstifadəçini redaktə et';
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'İstifadəçi yeniləndi';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Bax'),
            DeleteAction::make()->label('Sil'),
        ];
    }
}
