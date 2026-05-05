<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\DTOs\RegisterUserData;
use App\Domain\Identity\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

final class RegisterUserAction
{
    public function execute(RegisterUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
                'locale' => $data->locale,
            ])->fresh();

            event(new Registered($user));

            return $user;
        });
    }
}
