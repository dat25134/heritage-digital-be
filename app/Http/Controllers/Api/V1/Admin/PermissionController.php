<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Permission::query();
        if ($prefix = $request->get('module')) {
            $query->where('name', 'like', $prefix . '.%');
        }
        if ($guard = $request->get('guard', 'api')) {
            $query->where('guard_name', $guard);
        }
        
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
        
        $perms = $query->paginate((int) $request->get('per_page', 50));
        return response()->json(['data' => $perms, 'meta' => ['pagination' => $perms->toArray()], 'message' => '', 'errors' => null]);
    }
}


