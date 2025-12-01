<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AttributeController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//auth

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
    Route::delete('v1/products/{id}',[ProductController::class,'destroy']);
    Route::post('v1/products/{id}/attributes/{attributeId}',[ProductController::class,'storeAttribute']);
       Route::delete('v1/products/{id}/attributeValueId/{attributeProductId}',[ProductController::class,'destroyAttributeProduct']);
  });



Route::middleware(['auth:api','role:superadmin'])->group(
  function(){
    Route::get('v1/attributes',[AttributeController::class,'index']);
    Route::get('v1/attributes/{id}',[AttributeController::class,'show']);
    Route::post('v1/attributes',[AttributeController::class,'store']);
    Route::put('v1/attributes/{id}',[AttributeController::class,'update']);
    Route::delete('v1/attributes/{id}',[AttributeController::class,'destroy']);
    Route::post('v1/attributes/{id}/values',[AttributeController::class,'storeAttributesValue']);
  }
);





// Route::fallback(function () {
//     return response()->json([
//         'success' => false,
//         'message' => 'Page not found. This is an API-only application.',
//     ], 404);
// });
