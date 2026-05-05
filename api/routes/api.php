<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CvController;
use App\Http\Controllers\Api\V1\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {

    /*
    |--------------------------------------------------------------------------
    | Public auth routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register'])
            ->middleware('throttle:register')
            ->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:login')
            ->name('auth.login');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated routes
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Email verification
    |--------------------------------------------------------------------------
    */
    Route::get('/email/verify/{id}/{hash}', function (Request $request, int $id, string $hash) {
        return response()->json(['message' => 'Email verification endpoint']);
    })->middleware('signed')->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function (): void {

        Route::prefix('auth')->group(function (): void {
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
        });

        Route::apiResource('cvs', CvController::class);

        Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
        Route::get('/templates/{template:slug}', [TemplateController::class, 'show'])->name('templates.show');
    });

    Route::get('/health', fn (Request $request): array => [
        'status' => 'ok',
        'time' => now()->toIso8601String(),
    ])->name('health');
});
