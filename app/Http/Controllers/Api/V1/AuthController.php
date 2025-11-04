<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
/** @var \Tymon\JWTAuth\JWTGuard auth */

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = auth('api');
        $token = $guard->login($user);

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
            'meta' => [
                'token' => $this->formatToken($token),
            ],
            'message' => 'Register successful',
            'errors' => null,
        ], 201);
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'data' => (object) [],
                'meta' => (object) [],
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.'],
                ],
            ], 401);
        }

        $user = auth('api')->user();

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
            'meta' => [
                'token' => $this->formatToken((string) $token),
            ],
            'message' => 'Login successful',
            'errors' => null,
        ]);
    }

    public function me(): JsonResponse
    {
        $user = auth('api')->user();
        return response()->json([
            'data' => [
                'user' => $user,
            ],
            'meta' => (object) [],
            'message' => 'Current user',
            'errors' => null,
        ]);
    }

    public function refresh(): JsonResponse
    {
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = auth('api');
        $newToken = $guard->refresh();

        return response()->json([
            'data' => (object) [],
            'meta' => [
                'token' => $this->formatToken($newToken),
            ],
            'message' => 'Token refreshed',
            'errors' => null,
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = auth('api');
        $guard->logout();

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Logged out',
            'errors' => null,
        ]);
    }

    private function formatToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl', 60) * 60,
        ];
    }
}


