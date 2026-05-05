<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Identity\Actions\LoginUserAction;
use App\Domain\Identity\Actions\LogoutUserAction;
use App\Domain\Identity\Actions\RegisterUserAction;
use App\Domain\Identity\DTOs\LoginCredentials;
use App\Domain\Identity\DTOs\RegisterUserData;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function register(
        RegisterRequest $request,
        RegisterUserAction $action,
    ): JsonResponse {
        $user = $action->execute(RegisterUserData::fromArray($request->validated()));

        return UserResource::make($user)
            ->response()
            ->setStatusCode(201);
    }

    public function login(
        LoginRequest $request,
        LoginUserAction $action,
    ): UserResource {
        $user = $action->execute(
            LoginCredentials::fromArray($request->validated()),
            $request->ip() ?? '0.0.0.0',
        );

        return UserResource::make($user);
    }

    public function logout(Request $request, LogoutUserAction $action): JsonResponse
    {
        $action->execute($request);

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }
}
