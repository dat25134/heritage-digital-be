<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $roles = Role::query()->paginate((int) $request->get('per_page', 15));
        return response()->json(['data' => $roles, 'meta' => ['pagination' => $roles->toArray()], 'message' => '', 'errors' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['nullable', 'string', 'in:api,web'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $guard = $data['guard_name'] ?? 'api';
        $role = Role::query()->create(['name' => $data['name'], 'guard_name' => $guard]);

        if (!empty($data['permissions'])) {
            $perms = Permission::query()->whereIn('name', $data['permissions'])->where('guard_name', $guard)->get();
            $role->syncPermissions($perms);
        }

        return response()->json(['data' => $role, 'meta' => new \stdClass(), 'message' => 'Role created', 'errors' => null], 201);
    }

    public function show(int $id): JsonResponse
    {
        $role = Role::query()->with('permissions')->findOrFail($id);
        return response()->json(['data' => $role, 'meta' => new \stdClass(), 'message' => '', 'errors' => null]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
        ]);

        $role = Role::query()->findOrFail($id);
        $role->fill($data)->save();

        return response()->json(['data' => $role, 'meta' => new \stdClass(), 'message' => 'Role updated', 'errors' => null]);
    }

    public function destroy(int $id): JsonResponse
    {
        $role = Role::query()->findOrFail($id);
        $role->delete();
        return response()->json(['data' => new \stdClass(), 'meta' => new \stdClass(), 'message' => 'Role deleted', 'errors' => null]);
    }

    public function updatePermissions(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string'],
        ]);

        $role = Role::query()->findOrFail($id);
        $perms = Permission::query()
            ->whereIn('name', $data['permissions'])
            ->where('guard_name', $role->guard_name)
            ->get();
        $role->syncPermissions($perms);

        return response()->json(['data' => $role->load('permissions'), 'meta' => new \stdClass(), 'message' => 'Permissions synced', 'errors' => null]);
    }
}


