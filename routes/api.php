<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\CategoryController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::get('test',[AuthController::class,'test']);
Route::middleware('auth:api')->group(function () {
    Route::post('v1/auth/logout', [AuthController::class, 'logout']);
    Route::get('v1/auth/me', [AuthController::class, 'me']);
    Route::post('v1/auth/refresh',[AuthController::class,'refresh']);
    
});

//api for Roles and permissions
Route::middleware(['auth:api','role:superadmin'])->group(function () {
  Route::get('v1/roles', [RoleController::class, 'index']);
  Route::get('v1/roles/{id}',[RoleController::class,'show']);
  Route::post('v1/roles',[RoleController::class,'store']);
  Route::put('v1/roles/{id}',[RoleController::class,'update']);
  Route::delete('v1/roles/{id}',[RoleController::class,'destroy']);
  Route::get('v1/permissions',[PermissionController::class,'index']);
  Route::post('v1/roles/{role}/permissions',[RolePermissionController::class,'assignPermissions']);
  
});



//api for categories
Route::middleware(['auth:api','role:superadmin'])->group(function() {
   Route::get('v1/categories',[CategoryController::class,'index']);
   Route::post('v1/categories',[CategoryController::class,'store']);
   Route::get('v1/categories/{id}',[CategoryController::class,'show']);
  
});
