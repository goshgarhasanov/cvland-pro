<?php

declare(strict_types=1);

use App\Domain\Catalog\Models\Template;
use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;

it('lists only the authenticated user CVs', function (): void {
    $user = actingAsUser();
    Cv::factory()->count(3)->for($user)->create();
    Cv::factory()->count(2)->create();

    $this->getJson('/api/v1/cvs')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('creates a CV for the authenticated user', function (): void {
    $user = actingAsUser();
    $template = Template::factory()->create();

    $response = $this->postJson('/api/v1/cvs', [
        'title' => 'My Senior Backend CV',
        'template_id' => $template->id,
        'data' => ['summary' => 'Hello world'],
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'My Senior Backend CV');

    $this->assertDatabaseHas('cvs', [
        'user_id' => $user->id,
        'title' => 'My Senior Backend CV',
    ]);
});

it('rejects CV creation for guests', function (): void {
    $this->postJson('/api/v1/cvs', ['title' => 'Test'])
        ->assertUnauthorized();
});

it('forbids viewing another user CV', function (): void {
    $user = actingAsUser();
    $other = User::factory()->create();
    $cv = Cv::factory()->for($other)->create();

    $this->getJson("/api/v1/cvs/{$cv->id}")->assertForbidden();
});

it('updates own CV', function (): void {
    $user = actingAsUser();
    $cv = Cv::factory()->for($user)->create(['title' => 'Old']);

    $this->putJson("/api/v1/cvs/{$cv->id}", [
        'title' => 'New title',
        'data' => ['x' => 1],
    ])
        ->assertOk()
        ->assertJsonPath('data.title', 'New title');
});

it('forbids updating another user CV', function (): void {
    actingAsUser();
    $other = User::factory()->create();
    $cv = Cv::factory()->for($other)->create();

    $this->putJson("/api/v1/cvs/{$cv->id}", [
        'title' => 'Hijacked',
    ])->assertForbidden();
});

it('soft deletes own CV', function (): void {
    $user = actingAsUser();
    $cv = Cv::factory()->for($user)->create();

    $this->deleteJson("/api/v1/cvs/{$cv->id}")->assertNoContent();

    expect(Cv::find($cv->id))->toBeNull();
    expect(Cv::withTrashed()->find($cv->id))->not->toBeNull();
});
