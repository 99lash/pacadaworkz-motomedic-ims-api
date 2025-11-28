<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ProductController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//auth
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
   Route::put('v1/categories/{id}',[CategoryController::class,'update']);
   Route::delete('v1/categories/{id}',[CategoryController::class,'destroy']);
});

//api for brands
Route::middleware(['auth:api','role:superadmin'])->group(function(){
   Route::get('v1/brands',[BrandController::class,'index']);
   Route::get('v1/brands/{id}',[BrandController::class,'show']);
   Route::post('v1/brands',[BrandController::class,'store']);
   Route::put('v1/brands/{id}',[BrandController::class,'update']);
  Route::delete('v1/brands/{id}',[BrandController::class,'destroy']);

});


//api for products
Route::middleware(['auth:api','role:superadmin'])->group(
  function(){
    Route::get('v1/products',[ProductController::class,'index']);
    Route::get('v1/products/{id}',[ProductController::class,'show']);
    Route::post('v1/products',[ProductController::class,'store']);
    Route::put('v1/products/{id}',[ProductController::class,'update']);
    Route::delete('v1/products/{id}',[ProductController::class,'delete']);

    
  });
