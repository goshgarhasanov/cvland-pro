<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds defence-in-depth HTTP security headers to every response.
 *
 * Notes:
 * - HSTS is only emitted under HTTPS to avoid breaking local HTTP development.
 * - CSP is intentionally permissive for the JSON API (no inline JS surface)
 *   and tighter for HTML responses. Filament/Livewire uses inline styles and
 *   inline scripts so we allow `'unsafe-inline'` and `'unsafe-eval'` on the
 *   admin panel; the SPA marketing site is served from a separate origin.
 */
final class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff', false);
        $headers->set('X-Frame-Options', 'SAMEORIGIN', false);
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);
        $headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(self), usb=(), interest-cohort=()',
            false,
        );

        if ($request->isSecure()) {
            $headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload',
                false,
            );
        }

        // Tighten Content-Security-Policy on JSON API responses; HTML responses
        // (Filament admin) keep Filament's own policy intact.
        if ($request->is('api/*')) {
            $headers->set(
                'Content-Security-Policy',
                "default-src 'none'; frame-ancestors 'none'",
                false,
            );
        }

        return $response;
    }
}
