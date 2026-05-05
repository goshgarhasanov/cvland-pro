<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Yeni istifadəçi yarat';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'İstifadəçi yaradıldı';
    }
}
