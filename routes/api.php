<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolePermissionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Public routes
Route::post('v1/auth/login', [AuthController::class, 'login']);
// Route::get('/test', [AuthController::class, 'test']);

Route::middleware('auth:api')->group(function () {
    // Authentication endpoints
    Route::post('/v1/auth/logout', [AuthController::class, 'logout']);
    Route::get('/v1/auth/me', [AuthController::class, 'me']);
    Route::post('/v1/auth/refresh', [AuthController::class, 'refresh']);

    /**
     * User Management Endpoinnts
     * - Should be protected by auth layer [/]
     * - Should be protected by role layer [x], implement ko later
     */
    Route::prefix('/v1/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword']);
        Route::patch('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});

Route::middleware(['auth:api', 'role:superadmin'])->group(function () {
    Route::get('v1/roles', [RoleController::class, 'index']);
    Route::get('v1/roles/{id}', [RoleController::class, 'show']);
    Route::post('v1/roles', [RoleController::class, 'store']);
    Route::put('v1/roles/{id}', [RoleController::class, 'update']);
    Route::delete('v1/roles/{id}', [RoleController::class, 'destroy']);
    Route::get('v1/permissions', [PermissionController::class, 'index']);
    Route::post('v1/roles/{role}/permissions', [RolePermissionController::class, 'assignPermissions']);
});

// Route::fallback(function () {
//     return response()->json([
//         'success' => false,
//         'message' => 'Page not found. This is an API-only application.',
//     ], 404);
// });
