<?php

declare(strict_types=1);

it('emits defence-in-depth security headers on api responses', function (): void {
    $response = $this->getJson('/api/v1/health')->assertOk();

    expect($response->headers->get('X-Content-Type-Options'))->toBe('nosniff');
    expect($response->headers->get('X-Frame-Options'))->toBe('SAMEORIGIN');
    expect($response->headers->get('Referrer-Policy'))->toBe('strict-origin-when-cross-origin');
    expect($response->headers->get('Content-Security-Policy'))->toContain("frame-ancestors 'none'");
});
