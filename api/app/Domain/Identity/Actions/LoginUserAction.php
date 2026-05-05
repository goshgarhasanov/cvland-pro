<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\DTOs\LoginCredentials;
use App\Domain\Identity\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

final class LoginUserAction
{
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 60;

    public function execute(LoginCredentials $credentials, string $ipAddress): User
    {
        $key = $this->throttleKey($credentials->email, $ipAddress);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($key)]),
            ]);
        }

        if (! Auth::attempt($credentials->toCredentials(), $credentials->remember)) {
            RateLimiter::hit($key, self::DECAY_SECONDS);
            event(new Failed('web', null, $credentials->toCredentials()));

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($key);

        $request = request();
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    private function throttleKey(string $email, string $ipAddress): string
    {
        return 'login:' . mb_strtolower($email) . '|' . $ipAddress;
    }
}
