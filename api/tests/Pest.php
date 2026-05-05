<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

pest()->extend(Tests\TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function actingAsUser(?\App\Domain\Identity\Models\User $user = null): \App\Domain\Identity\Models\User
{
    $user ??= \App\Domain\Identity\Models\User::factory()->create();

    test()->actingAs($user);

    return $user;
}
