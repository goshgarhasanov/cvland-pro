<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LogoutUserAction
{
    public function execute(Request $request): void
    {
        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }
}
