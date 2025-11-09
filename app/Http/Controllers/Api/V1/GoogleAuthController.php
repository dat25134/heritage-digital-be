<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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

        // Use Spatie's efficient methods to get roles and permissions
        // getRoleNames() returns a collection of role names directly (more efficient)
        $roles = $user->getRoleNames();
        
        // Get permissions efficiently by querying only the name column directly from database
        // This avoids loading full permission objects and relationships into memory
        // Query both direct permissions and permissions from roles
        $directPermissions = DB::table('permissions')
            ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_has_permissions.model_type', User::class)
            ->where('model_has_permissions.model_id', $user->id)
            ->select('permissions.name')
            ->pluck('name');
        
        $rolePermissions = DB::table('permissions')
            ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->join('model_has_roles', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', $user->id)
            ->select('permissions.name')
            ->pluck('name');
        
        // Merge and get unique permission names
        $permissions = $directPermissions->merge($rolePermissions)->unique()->values();

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'roles' => $roles,
                'permissions' => $permissions,
            ],
            'meta' => [
                'token' => $this->formatToken((string) $token),
            ],
            'message' => 'Google login successful',
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


