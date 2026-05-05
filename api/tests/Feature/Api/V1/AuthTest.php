<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;

it('registers a new user', function (): void {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.email', 'jane@example.com')
        ->assertJsonPath('data.name', 'Jane Doe');

    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'name' => 'Jane Doe',
    ]);
});

it('rejects registration with invalid email', function (): void {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Bad',
        'email' => 'not-an-email',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('rejects registration with weak password', function (): void {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('logs an existing user in', function (): void {
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'Password123!',
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'login@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.id', $user->id);

    expect(auth()->check())->toBeTrue();
});

it('rejects login with wrong password', function (): void {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'Password123!',
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'login@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('returns the authenticated user from /me', function (): void {
    $user = actingAsUser();

    $this->getJson('/api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.email', $user->email);
});

it('rejects /me when unauthenticated', function (): void {
    $this->getJson('/api/v1/auth/me')->assertUnauthorized();
});

it('logs the user out', function (): void {
    actingAsUser();

    $this->postJson('/api/v1/auth/logout')->assertOk();
});
