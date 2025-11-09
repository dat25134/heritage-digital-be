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
        $query = Role::query();
        
        // Sorting
        $sort = $request->string('sort')->toString() ?: 'id';
        $order = $request->string('order')->toString();
        $column = ltrim($sort, '-');
        
        // Use order parameter if provided, otherwise use sort prefix
        if ($order !== null && in_array(strtolower($order), ['asc', 'desc'], true)) {
            $direction = strtolower($order);
        } else {
            $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        }
        
        $allowed = ['id', 'name', 'guard_name', 'created_at', 'updated_at'];
        if (in_array($column, $allowed, true)) {
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('id', $direction);
        }
        
        $roles = $query->paginate((int) $request->get('per_page', 15));
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
            'permissions' => ['present', 'array'],
            'permissions.*' => ['string'],
        ]);

        $role = Role::query()->findOrFail($id);
        
        // If permissions array is empty, syncPermissions will remove all permissions from the role
        // If permissions array has values, it will sync to only those permissions
        if (empty($data['permissions'])) {
            $role->syncPermissions([]);
        } else {
            $perms = Permission::query()
                ->whereIn('name', $data['permissions'])
                ->where('guard_name', $role->guard_name)
                ->get();
            $role->syncPermissions($perms);
        }

        return response()->json(['data' => $role->load('permissions'), 'meta' => new \stdClass(), 'message' => 'Permissions synced', 'errors' => null]);
    }
}


