<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()->with(['roles', 'permissions']);

        // Search by name or email
        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->string('role')->toString()) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // Sorting
        $sort = $request->string('sort', '-id')->toString();
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        if (in_array($column, ['id', 'name', 'email', 'created_at', 'updated_at'])) {
            $query->orderBy($column, $direction);
        } else {
            $query->orderByDesc('id');
        }

        $users = $query->paginate($request->integer('per_page', 15));

        $data = $users->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'provider' => $user->provider,
                'avatar_path' => $user->avatar_path,
                'roles' => $user->roles->map(fn($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                ]),
                'permissions' => $user->permissions->map(fn($perm) => [
                    'id' => $perm->id,
                    'name' => $perm->name,
                    'guard_name' => $perm->guard_name,
                ]),
                'created_at' => $user->created_at?->toISOString(),
                'updated_at' => $user->updated_at?->toISOString(),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ],
            ],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::query()
            ->with(['roles', 'permissions'])
            ->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'provider' => $user->provider,
                'provider_id' => $user->provider_id,
                'avatar_path' => $user->avatar_path,
                'roles' => $user->roles->map(fn($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                ]),
                'permissions' => $user->permissions->map(fn($perm) => [
                    'id' => $perm->id,
                    'name' => $perm->name,
                    'guard_name' => $perm->guard_name,
                ]),
                'created_at' => $user->created_at?->toISOString(),
                'updated_at' => $user->updated_at?->toISOString(),
            ],
            'meta' => (object) [],
            'message' => '',
            'errors' => null,
        ]);
    }
}
