<?php

declare(strict_types=1);

it('returns ok from /up', function (): void {
    $this->get('/up')->assertOk();
});

it('returns ok from /api/v1/health', function (): void {
    $this->getJson('/api/v1/health')
        ->assertOk()
        ->assertJsonPath('status', 'ok');
});
