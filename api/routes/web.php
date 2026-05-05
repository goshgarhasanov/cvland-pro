<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

// Dev-only auto-login for screenshot tooling. Disabled outside the local env.
Route::get('/__dev/login-admin', function () {
    abort_unless(app()->environment('local'), 404);

    $user = User::query()->where('email', 'admin@admin.com')->first();
    abort_unless($user, 404, 'admin user missing');

    Auth::login($user);
    request()->session()->regenerate();

    return redirect('/admin');
});
