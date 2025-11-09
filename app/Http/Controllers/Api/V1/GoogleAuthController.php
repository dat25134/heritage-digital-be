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
            // Refresh user to ensure it's properly loaded from database
            $user->refresh();
            // Automatically assign 'viewer' role to new users
            $user->assignRole('viewer');
        }

        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = auth('api');
        $token = $guard->login($user);

        // Initialize empty collections for roles and permissions
        $roles = collect();
        $permissions = collect();

        // Only query if user has been saved to database (has an ID)
        // For new users without roles, return empty collections immediately
        if ($user->id) {
            // Quick check: if user has any roles or direct permissions
            // Use a single query to check both to minimize database calls
            $userHasRolesOrPermissions = DB::table('model_has_roles')
                ->where('model_type', User::class)
                ->where('model_id', $user->id)
                ->exists() || DB::table('model_has_permissions')
                ->where('model_type', User::class)
                ->where('model_id', $user->id)
                ->exists();

            // Only query if user actually has roles or permissions
            if ($userHasRolesOrPermissions) {
                // Check if user has any roles
                $hasRoles = DB::table('model_has_roles')
                    ->where('model_type', User::class)
                    ->where('model_id', $user->id)
                    ->exists();

                // Get roles efficiently by querying only the name column directly from database
                if ($hasRoles) {
                    $roles = DB::table('roles')
                        ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('model_has_roles.model_type', User::class)
                        ->where('model_has_roles.model_id', $user->id)
                        ->select('roles.name')
                        ->pluck('name');
                }

                // Get direct permissions (only if user has direct permissions)
                $hasDirectPermissions = DB::table('model_has_permissions')
                    ->where('model_type', User::class)
                    ->where('model_id', $user->id)
                    ->exists();

                $directPermissions = collect();
                if ($hasDirectPermissions) {
                    $directPermissions = DB::table('permissions')
                        ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                        ->where('model_has_permissions.model_type', User::class)
                        ->where('model_has_permissions.model_id', $user->id)
                        ->select('permissions.name')
                        ->pluck('name');
                }

                // Get permissions from roles (only if user has roles)
                $rolePermissions = collect();
                if ($hasRoles) {
                    $rolePermissions = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->join('model_has_roles', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
                        ->where('model_has_roles.model_type', User::class)
                        ->where('model_has_roles.model_id', $user->id)
                        ->select('permissions.name')
                        ->pluck('name');
                }

                // Merge and get unique permission names
                $permissions = $directPermissions->merge($rolePermissions)->unique()->values();
            }
        }

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


