<?php

declare(strict_types=1);

namespace App\Domain\CV\Policies;

use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;

final class CvPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Cv $cv): bool
    {
        return $user->id === $cv->user_id || $cv->is_public;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Cv $cv): bool
    {
        return $user->id === $cv->user_id;
    }

    public function delete(User $user, Cv $cv): bool
    {
        return $user->id === $cv->user_id;
    }
}
