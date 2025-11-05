<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GoogleAuthController;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\PermissionController;
use App\Http\Controllers\Api\V1\Admin\UserRolePermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::prefix('v1/auth')->name('api.v1.auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('google/callback', [GoogleAuthController::class, 'callback']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('throttle:10,1');
    });
});

Route::prefix('v1/admin')->middleware(['auth:api', 'role:admin'])->name('api.v1.admin.')->group(function () {
    // Roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
    Route::put('roles/{id}/permissions', [RoleController::class, 'updatePermissions']);

    // Permissions
    Route::get('permissions', [PermissionController::class, 'index']);

    // User role/permission assignment
    Route::get('users/{id}/roles', [UserRolePermissionController::class, 'getRoles']);
    Route::put('users/{id}/roles', [UserRolePermissionController::class, 'syncRoles']);
    Route::get('users/{id}/permissions', [UserRolePermissionController::class, 'getPermissions']);
    Route::put('users/{id}/permissions', [UserRolePermissionController::class, 'syncPermissions']);
});