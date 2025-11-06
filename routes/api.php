<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GoogleAuthController;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\PermissionController;
use App\Http\Controllers\Api\V1\Admin\UserRolePermissionController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Media\EntityMediaController;
use App\Http\Controllers\Api\V1\ImageIntros\ImageIntroController;
use App\Http\Controllers\Api\V1\Topics\TopicController;
use App\Http\Controllers\Api\V1\Videos\VideoController;

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
    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);

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

Route::prefix('v1')->middleware(['auth:api'])->name('api.v1.')->group(function () {
    // Image Intros
    Route::get('image-intros', [ImageIntroController::class, 'index'])->middleware(['permission:image-intros.read']);
    Route::post('image-intros', [ImageIntroController::class, 'store'])->middleware(['permission:image-intros.create']);
    Route::get('image-intros/{id}', [ImageIntroController::class, 'show'])->middleware(['permission:image-intros.read']);
    Route::put('image-intros/{id}', [ImageIntroController::class, 'update'])->middleware(['permission:image-intros.update']);
    Route::delete('image-intros/{id}', [ImageIntroController::class, 'destroy'])->middleware(['permission:image-intros.delete']);
    Route::post('image-intros/{id}/publish', [ImageIntroController::class, 'publish'])->middleware(['permission:image-intros.update']);

    // Topics
    Route::get('topics', [TopicController::class, 'index'])->middleware(['permission:topics.read']);
    Route::post('topics', [TopicController::class, 'store'])->middleware(['permission:topics.create']);
    Route::get('topics/{id}', [TopicController::class, 'show'])->middleware(['permission:topics.read']);
    Route::put('topics/{id}', [TopicController::class, 'update'])->middleware(['permission:topics.update']);
    Route::delete('topics/{id}', [TopicController::class, 'destroy'])->middleware(['permission:topics.delete']);

    Route::get('topics/{id}/media', [TopicController::class, 'listMedia'])->middleware(['permission:topics.read']);
    Route::post('topics/{id}/media/attach', [TopicController::class, 'attachMedia'])->middleware(['permission:topics.update']);
    Route::post('topics/{id}/media/detach', [TopicController::class, 'detachMedia'])->middleware(['permission:topics.update']);
    Route::put('topics/{id}/media/order', [TopicController::class, 'updateMediaOrder'])->middleware(['permission:topics.order']);

    // Videos
    Route::get('videos', [VideoController::class, 'index'])->middleware(['permission:videos.read']);
    Route::post('videos', [VideoController::class, 'store'])->middleware(['permission:videos.create']);
    Route::get('videos/{id}', [VideoController::class, 'show'])->middleware(['permission:videos.read']);
    Route::put('videos/{id}', [VideoController::class, 'update'])->middleware(['permission:videos.update']);
    Route::delete('videos/{id}', [VideoController::class, 'destroy'])->middleware(['permission:videos.delete']);
    Route::post('videos/{id}/publish', [VideoController::class, 'publish'])->middleware(['permission:videos.publish']);
    Route::post('videos/{id}/thumbnail', [VideoController::class, 'uploadThumbnail'])->middleware(['permission:videos.update']);
    Route::put('videos/order', [VideoController::class, 'updateOrder'])->middleware(['permission:videos.order']);

    // Generic entity media endpoints (placed after Topics to avoid route conflicts)
    Route::post('{entity}/{id}/media/{collection}', [EntityMediaController::class, 'store'])
        ->middleware(['permission:images.create|images.update']);
    Route::get('{entity}/{id}/media', [EntityMediaController::class, 'index']);
    Route::delete('media/{mediaId}', [EntityMediaController::class, 'destroy'])
        ->middleware(['permission:images.delete']);
});

