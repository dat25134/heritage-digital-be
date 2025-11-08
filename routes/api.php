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
use App\Http\Controllers\Api\V1\Posts\PostController;
use App\Http\Controllers\Api\V1\Research\ResearchPaperController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\Books\BookController;

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
    Route::get('image-intros/{id}/videos', [VideoController::class, 'indexByIntro'])->middleware(['permission:videos.read']);
    Route::post('image-intros/{id}/videos', [VideoController::class, 'storeByIntro'])->middleware(['permission:videos.create']);
    Route::get('videos/{id}', [VideoController::class, 'show'])->middleware(['permission:videos.read']);
    Route::put('videos/{id}', [VideoController::class, 'update'])->middleware(['permission:videos.update']);
    Route::delete('videos/{id}', [VideoController::class, 'destroy'])->middleware(['permission:videos.delete']);
    Route::post('videos/{id}/publish', [VideoController::class, 'publish'])->middleware(['permission:videos.publish']);
    Route::post('videos/{id}/thumbnail', [VideoController::class, 'uploadThumbnail'])->middleware(['permission:videos.update']);
    Route::put('videos/order', [VideoController::class, 'updateOrder'])->middleware(['permission:videos.order']);

    // Posts
    Route::get('posts', [PostController::class, 'index'])->middleware(['permission:posts.read']);
    Route::post('posts', [PostController::class, 'store'])->middleware(['permission:posts.create']);
    Route::get('posts/{id}', [PostController::class, 'show'])->middleware(['permission:posts.read']);
    Route::put('posts/{id}', [PostController::class, 'update'])->middleware(['permission:posts.update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy'])->middleware(['permission:posts.delete']);
    Route::post('posts/{id}/publish', [PostController::class, 'publish'])->middleware(['permission:posts.publish']);

    // Research Papers
    Route::get('papers', [ResearchPaperController::class, 'index'])->middleware(['permission:papers.read']);
    Route::post('papers', [ResearchPaperController::class, 'store'])->middleware(['permission:papers.create']);
    Route::get('image-intros/{id}/papers', [ResearchPaperController::class, 'indexByIntro'])->middleware(['permission:papers.read']);
    Route::post('image-intros/{id}/papers', [ResearchPaperController::class, 'storeByIntro'])->middleware(['permission:papers.create']);
    Route::get('papers/{id}', [ResearchPaperController::class, 'show'])->middleware(['permission:papers.read']);
    Route::put('papers/{id}', [ResearchPaperController::class, 'update'])->middleware(['permission:papers.update']);
    Route::delete('papers/{id}', [ResearchPaperController::class, 'destroy'])->middleware(['permission:papers.delete']);
    Route::post('papers/{id}/publish', [ResearchPaperController::class, 'publish'])->middleware(['permission:papers.publish']);

    // Documents
    Route::get('documents', [DocumentController::class, 'index'])->middleware(['permission:documents.read']);
    Route::post('documents', [DocumentController::class, 'store'])->middleware(['permission:documents.create']);
    Route::get('image-intros/{id}/documents', [DocumentController::class, 'indexByIntro'])->middleware(['permission:documents.read']);
    Route::post('image-intros/{id}/documents', [DocumentController::class, 'storeByIntro'])->middleware(['permission:documents.create']);
    Route::get('documents/{document}', [DocumentController::class, 'show'])->middleware(['permission:documents.read']);
    Route::put('documents/{document}', [DocumentController::class, 'update'])->middleware(['permission:documents.update']);
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->middleware(['permission:documents.delete']);
    Route::post('documents/{document}/publish', [DocumentController::class, 'publish'])->middleware(['permission:documents.publish']);
    Route::post('documents/{document}/unpublish', [DocumentController::class, 'unpublish'])->middleware(['permission:documents.publish']);

    // Books
    Route::get('books', [BookController::class, 'index'])->middleware(['permission:books.read']);
    Route::post('books', [BookController::class, 'store'])->middleware(['permission:books.create']);
    Route::get('image-intros/{id}/books', [BookController::class, 'indexByIntro'])->middleware(['permission:books.read']);
    Route::post('image-intros/{id}/books', [BookController::class, 'storeByIntro'])->middleware(['permission:books.create']);
    Route::get('books/{book}', [BookController::class, 'show'])->middleware(['permission:books.read']);
    Route::put('books/{book}', [BookController::class, 'update'])->middleware(['permission:books.update']);
    Route::delete('books/{book}', [BookController::class, 'destroy'])->middleware(['permission:books.delete']);
    Route::post('books/{book}/publish', [BookController::class, 'publish'])->middleware(['permission:books.publish']);
    Route::get('books/{book}/media', [BookController::class, 'listMedia'])->middleware(['permission:books.read']);
    //Route::post('books/{book}/media/{collection}', [BookController::class, 'uploadMedia'])->middleware(['permission:books.upload','throttle:uploads']);

    // Generic entity media endpoints (placed after Topics to avoid route conflicts)
    Route::post('{entity}/{id}/media/{collection}', [EntityMediaController::class, 'store'])
        ->middleware(['permission:images.create|images.update']);
    Route::get('{entity}/{id}/media', [EntityMediaController::class, 'index']);
    Route::delete('media/{mediaId}', [EntityMediaController::class, 'destroy'])
        ->middleware(['permission:images.delete']);
});

