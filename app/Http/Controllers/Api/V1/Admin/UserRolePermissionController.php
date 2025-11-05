<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionController extends Controller
{
    public function getRoles(int $userId): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        return response()->json(['data' => $user->roles, 'meta' => new \stdClass(), 'message' => '', 'errors' => null]);
    }

    public function syncRoles(Request $request, int $userId): JsonResponse
    {
        $data = $request->validate([
            'roles' => ['required', 'array'],
            'roles.*' => ['string'],
        ]);

        $user = User::query()->findOrFail($userId);
        $roles = Role::query()->whereIn('name', $data['roles'])->where('guard_name', 'api')->pluck('name')->toArray();
        $user->syncRoles($roles);

        return response()->json(['data' => $user->roles, 'meta' => new \stdClass(), 'message' => 'Roles synced', 'errors' => null]);
    }

    public function getPermissions(int $userId): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        return response()->json(['data' => $user->permissions, 'meta' => new \stdClass(), 'message' => '', 'errors' => null]);
    }

    public function syncPermissions(Request $request, int $userId): JsonResponse
    {
        $data = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string'],
        ]);

        $user = User::query()->findOrFail($userId);
        $perms = Permission::query()->whereIn('name', $data['permissions'])->where('guard_name', 'api')->get();
        $user->syncPermissions($perms);

        return response()->json(['data' => $user->permissions, 'meta' => new \stdClass(), 'message' => 'Permissions synced', 'errors' => null]);
    }
}


