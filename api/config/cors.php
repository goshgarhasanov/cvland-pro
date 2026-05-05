<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| CORS configuration
|--------------------------------------------------------------------------
|
| The SPA runs on a separate origin from the API and uses cookie-based
| Sanctum auth, so we MUST allow credentials and we MUST NOT use a
| wildcard origin (the browser refuses `*` together with credentials).
|
| Allowed origins are sourced from the `SPA_URL` and `CORS_ALLOWED_ORIGINS`
| env vars so production deployments can pin them tightly.
|
*/

$origins = array_filter(array_map(
    static fn (string $value): string => trim($value),
    explode(',', (string) env('CORS_ALLOWED_ORIGINS', (string) env('SPA_URL', 'http://localhost:5173'))),
));

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => array_values($origins) ?: ['http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'Origin',
        'X-Requested-With',
        'X-XSRF-TOKEN',
        'X-CSRF-TOKEN',
    ],

    'exposed_headers' => [],

    'max_age' => 3600,

    'supports_credentials' => true,
];
