<?php

declare(strict_types=1);

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // Trust only the proxies explicitly listed in the TRUSTED_PROXIES env var.
        // A wildcard ('*') is dangerous in production because it lets any client
        // spoof X-Forwarded-For headers; we keep it as a fallback only when the
        // operator has not configured the variable.
        $proxies = (string) env('TRUSTED_PROXIES', '');
        $middleware->trustProxies(
            at: $proxies === '' ? '*' : array_map('trim', explode(',', $proxies)),
        );

        $middleware->throttleApi();

        // Append our defence-in-depth headers to every response (web + api).
        $middleware->append(SecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            'api/v1/billing/stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request): bool => $request->is('api/*') || $request->expectsJson(),
        );
    })
    ->create();
