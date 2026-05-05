<?php

declare(strict_types=1);

namespace App\Domain\Identity\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of \App\Domain\Identity\Models\User
 *
 * @extends Builder<TModel>
 */
class UserBuilder extends Builder
{
    public function verified(): self
    {
        return $this->whereNotNull('email_verified_at');
    }

    public function unverified(): self
    {
        return $this->whereNull('email_verified_at');
    }

    public function withEmail(string $email): self
    {
        return $this->where('email', mb_strtolower(trim($email)));
    }
}
