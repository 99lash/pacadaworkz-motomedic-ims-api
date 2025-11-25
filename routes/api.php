<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::get('test', [AuthController::class, 'test']);
Route::middleware('auth:api')->group(function () {
    Route::post('v1/auth/logout', [AuthController::class, 'logout']);
    Route::get('v1/auth/me', [AuthController::class, 'me']);
    Route::post('v1/auth/refresh', [AuthController::class, 'refresh']);
});

/**
 * Get all users paginated, searchable, filterable
 **/
Route::prefix('/v1/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::post('/{id}', [UserController::class, 'activate']);
    Route::post('/{id}', [UserController::class, 'deactivate']);
    Route::post('/{id}', [UserController::class, 'resetPassword']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
