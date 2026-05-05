<?php

declare(strict_types=1);

namespace App\Domain\Identity\Models;

use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\Builders\UserBuilder;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'avatar_path', 'locale'])]
#[Hidden(['password', 'remember_token'])]
#[UseFactory(UserFactory::class)]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cvs(): HasMany
    {
        return $this->hasMany(Cv::class);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $path = $this->getAttributes()['avatar_path'] ?? null;

            return $path ? asset('storage/' . $path) : null;
        });
    }

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }
}
