<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PosController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\StocksController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\GoogleAuthController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\PurchaseController;
use App\Http\Controllers\API\SalesController;
use App\Http\Controllers\API\DashboardController;

Route::prefix('v1')->group(function () {
    // Public routes (Unauthenticated)
    Route::middleware('guest.api')->group(function () {
        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/login/google', [GoogleAuthController::class, 'login']);
        });
    });

    // Private routes (Authenticated)
    Route::middleware('auth:api')->group(function () {
        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });

        Route::middleware('role:superadmin,admin')->group(function () {

            // Users
            Route::prefix('users')->group(function () {
                Route::get('/', [UserController::class, 'index']);
                //modules middleware of users
                Route::middleware('modules:Users')->group(function () {
                    Route::get('/{id}', [UserController::class, 'show'])->middleware('permissions:View');
                    Route::post('/', [UserController::class, 'store'])->middleware('permissions:Create');
                    Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->middleware('permissions:Edit');;
                    Route::patch('/{id}', [UserController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permissions:Delete');
                });
            });

            // Roles
            Route::prefix('roles')->group(function () {
                Route::get('/', [RoleController::class, 'index']);

                //module middleware of roles
                Route::middleware('modules:Roles')->group(function () {
                    Route::get('/{id}', [RoleController::class, 'show'])->middleware('permissions:View');
                    Route::post('/', [RoleController::class, 'store'])->middleware('permissions:Create');
                    Route::put('/{id}', [RoleController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('permissions:Delete');
                });
                Route::post('/{role}/permissions', [RolePermissionController::class, 'assignPermissions']);
            });

            // Permissions
            Route::prefix('permissions')->group(function () {
                Route::get('/', [PermissionController::class, 'index']);
            });

            // Categories
            Route::prefix('categories')->group(function () {
                // categories module middleware
                Route::middleware('modules:Categories')->group(function () {
                    Route::get('/', [CategoryController::class, 'index'])->middleware('permissions:View');
                    Route::post('/', [CategoryController::class, 'store'])->middleware('permissions:Create');
                    Route::get('/{id}', [CategoryController::class, 'show'])->middleware('permissions:View');
                    Route::put('/{id}', [CategoryController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [CategoryController::class, 'destroy'])->middleware('permissions:Delete');
                });
            });

            // Brands
            Route::prefix('brands')->group(function () {
                //brands module middleware
                Route::middleware('modules:Brands')->group(function () {
                    Route::get('/', [BrandController::class, 'index'])->middleware('permissions:View');
                    Route::get('/{id}', [BrandController::class, 'show'])->middleware('permissions:View');
                    Route::post('/', [BrandController::class, 'store'])->middleware('permissions:Create');
                    Route::put('/{id}', [BrandController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [BrandController::class, 'destroy'])->middleware('permissions:Delete');
                });
            });

            // Attributes
            Route::prefix('attributes')->group(function () {
                // attribute module middleware
                Route::middleware('modules:Attributes')->group(function () {
                    Route::get('/', [AttributeController::class, 'index'])->middleware('permissions:View');
                    Route::get('/{id}', [AttributeController::class, 'show'])->middleware('permissions:View');
                    Route::post('/', [AttributeController::class, 'store'])->middleware('permissions:Create');
                    Route::put('/{id}', [AttributeController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [AttributeController::class, 'destroy'])->middleware('permissions:Delete');
                });

                Route::post('/{id}/values', [AttributeController::class, 'storeAttributesValue']);
            });

            // Products
            Route::middleware('modules:Products')->prefix('products')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->middleware('permissions:View');
                Route::get('/{id}', [ProductController::class, 'show'])->middleware('permissions:View');
                Route::post('/', [ProductController::class, 'store'])->middleware('permissions:Create');
                Route::put('/{id}', [ProductController::class, 'update'])->middleware('permissions:Edit');
                Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('permissions:Delete');

                Route::get('/export', [ProductController::class, 'export']);
                Route::post('/{id}/attributes/{attributeId}', [ProductController::class, 'storeAttribute']);
                Route::delete('/{id}/attributeValueId/{attributeProductId}', [ProductController::class, 'destroyAttributeProduct']);
            });

            //inventory
            Route::prefix('inventory')->group(function () {
                Route::middleware('modules:Inventory')->group(function () {
                    Route::get('/', [InventoryController::class, 'index'])->middleware('permissions:View');
                    Route::post('/', [InventoryController::class, 'store'])->middleware('permissions:Create');
                    Route::get('/{id}', [InventoryController::class, 'show'])->middleware('permissions:View');
                    Route::patch('/{id}', [InventoryController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [InventoryController::class, 'destroy'])->middleware('permissions:Delete');
                });
            });

            //suppliers
            Route::prefix('suppliers')->group(function () {
                // suppliers module middleware
                Route::middleware('modules:Suppliers')->group(function () {
                    Route::get('/', [SupplierController::class, 'index'])->middleware('permissions:View');
                    Route::post('/', [SupplierController::class, 'store'])->middleware('permissions:Create');
                    Route::get('/{id}', [SupplierController::class, 'show'])->middleware('permissions:View');
                    Route::patch('/{id}', [SupplierController::class, 'update'])->middleware('permissions:Edit');
                    Route::delete('/{id}', [SupplierController::class, 'destroy'])->middleware('permissions:Delete');
                });
            });

            // Stock-movements
            Route::prefix('stock-movements')->group(function () {
                Route::get('/', [StocksController::class, 'showStockMovements']);
                Route::get('/{id}', [StocksController::class, 'showStockMovementsById']);
                Route::get('/cv/export', [StocksController::class, 'exportStockMovements']);
            });

            // Stock-adjustments
            Route::prefix('stock-adjustments')->group(function () {
                Route::get('/', [StocksController::class, 'showStockAdjustments']);
                Route::get('/{id}', [StocksController::class, 'showStockAdjustmentsById']);
                Route::get('/cv/export', [StocksController::class, 'exportStockAdjustments']);
            });

            //POS
            Route::prefix('pos')->middleware('modules:POS')->group(function () {
                //Cart
                Route::prefix('cart')->group(function () {
                    //pos module middleware
                    Route::get('/', [PosController::class, 'show'])->middleware('permissions:Access');
                    Route::post('/add-item', [PosController::class, 'store'])->middleware('permissions:Access');
                    Route::patch('/update-item/{id}', [PosController::class, 'update'])->middleware('permissions:Access');
                    Route::delete('/remove-item/{id}', [PosController::class, 'delete'])->middleware('permissions:Access');
                    Route::post('/clear', [PosController::class, 'clearCart'])->middleware('permissions:Access');
                    Route::post('/apply-discount', [PosController::class, 'applyDiscount'])->middleware('permissions:Access');
                });

                Route::post('/checkout', [PosController::class, 'checkoutCart'])->middleware('permissions:Create Transaction');
            });
            //purchase
            Route::prefix('purchases')->middleware('modules:Purchases')->group(function(){
              Route::get('/', [PurchaseController::class, 'index'])->middleware('permissions:View');
              Route::get('/{id}',[PurchaseController::class, 'show'])->middleware('permissions:View');
              Route::post('/',[PurchaseController::class,'store'])->middleware('permissions:Create');
              Route::patch('/{id}',[PurchaseController::class, 'update'])->middleware('permissions:Edit');
              Route::delete('/{id}',[PurchaseController::class,'destroy'])->middleware('permissions:Delete');
            });


            //Sales
            Route::prefix('sales')->group(function () {
                Route::get('/', [SalesController::class, 'index']);
                Route::get('/{id}', [SalesController::class, 'show']);
            });


            //Dashboard
            Route::prefix('dashboard')->group(function(){
                Route::get('/stats',[DashboardController::class,'showStats']);
                Route::get('/charts/sales-trend',[DashboardController::class,'showSalesTrend']);
                Route::get('/charts/top-products',[DashboardController::class,'showTopProducts']);
            });
        });
    });
});
