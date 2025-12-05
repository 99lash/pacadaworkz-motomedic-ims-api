<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\ModuleMiddleware;
use App\Http\Middleware\PermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        Route::aliasMiddleware('role', RoleMiddleware::class);
        Route::aliasMiddleware('modules', ModuleMiddleware::class);
        Route::aliasMiddleware('permissions', PermissionMiddleware::class);
    }
}
