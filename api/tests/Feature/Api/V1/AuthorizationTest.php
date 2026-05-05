<?php

declare(strict_types=1);

use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;

it('rejects updating another users CV with the explicit policy on update()', function (): void {
    actingAsUser();
    $other = User::factory()->create();
    $cv = Cv::factory()->for($other)->create();

    // The controller authorize('update', $cv) call should fire even if
    // the form request authorize() was previously the only gate.
    $this->putJson("/api/v1/cvs/{$cv->id}", [
        'title' => 'Hijack attempt',
        'data' => ['x' => 1],
    ])->assertForbidden();
});

it('rejects deleting another users CV', function (): void {
    actingAsUser();
    $other = User::factory()->create();
    $cv = Cv::factory()->for($other)->create();

    $this->deleteJson("/api/v1/cvs/{$cv->id}")->assertForbidden();
});

it('caps per_page on the cv index to prevent unbounded enumeration', function (): void {
    $user = actingAsUser();
    Cv::factory()->count(3)->for($user)->create();

    // A huge per_page must be silently clamped to <=100 by the controller.
    $response = $this->getJson('/api/v1/cvs?per_page=99999')->assertOk();
    $perPage = (int) data_get($response->json(), 'meta.per_page', 0);

    expect($perPage)->toBeLessThanOrEqual(100);
});
