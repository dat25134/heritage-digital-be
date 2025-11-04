<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
/** @var \Tymon\JWTAuth\JWTGuard auth */

class GoogleAuthController extends Controller
{
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return response()->json([
            'data' => ['url' => $url],
            'meta' => (object) [],
            'message' => 'Google redirect URL',
            'errors' => null,
        ]);
    }

    public function callback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?? 'Google User';
        $providerId = $googleUser->getId();
        $avatar = $googleUser->getAvatar();

        $user = User::where('email', $email)->first();

        if ($user) {
            if (empty($user->provider_id)) {
                $user->provider = 'google';
                $user->provider_id = $providerId;
                if ($avatar) {
                    $user->avatar_path = $avatar;
                }
                $user->save();
            }
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(uniqid('google_', true)),
                'provider' => 'google',
                'provider_id' => $providerId,
                'avatar_path' => $avatar,
            ]);
        }

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
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => (int) config('jwt.ttl', 60) * 60,
                ],
            ],
            'message' => 'Google login successful',
            'errors' => null,
        ]);
    }
}


