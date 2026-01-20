<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ActivityLogService;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Execute controller first
        $response = $next($request);

        try {
            // Use 'api' guard (JWT) to get authenticated user
            $user = auth('api')->user();

            if ($user) {
                // 1️⃣ Get all URL segments
                $segments = $request->segments();

                // 2️⃣ Take last segment, skip numeric IDs
                $lastSegment = end($segments);
                $module = is_numeric($lastSegment) ? prev($segments) : $lastSegment;

                // 3️⃣ Log activity
                app(ActivityLogService::class)->log(
                    module: $module,
                    action: $this->mapAction($request->method()),
                    description: ucfirst($this->mapAction($request->method())) . ' ' . $module,
                    user_id: $user->id // optional
                );
            }
        } catch (\Exception $e) {
            // Log error but don't break API response
            \Log::error('ActivityLogMiddleware failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Map HTTP method to action name
     */
    private function mapAction(string $method): string
    {
        return match ($method) {
            'GET'    => 'View',
            'POST'   => 'Create',
            'PUT',
            'PATCH'  => 'Edit',
            'DELETE' => 'Delete',
            default  => 'performed',
        };
    }

    /**
     * Detect module based on request path (optional, can be used if needed)
     */
    private function detectModule(string $path): string
    {
        return match (true) {
            str_starts_with($path, 'api/v1/auth') => 'Authentication',
            str_starts_with($path, 'api/v1/users') => 'Users',
            str_starts_with($path, 'api/v1/roles') => 'Roles',
            str_starts_with($path, 'api/v1/permissions') => 'Permissions',
            str_starts_with($path, 'api/v1/categories') => 'Categories',
            str_starts_with($path, 'api/v1/brands') => 'Brands',
            str_starts_with($path, 'api/v1/attributes') => 'Attributes',
            str_starts_with($path, 'api/v1/products') => 'Products',
            str_starts_with($path, 'api/v1/suppliers') => 'Suppliers',
            str_starts_with($path, 'api/v1/inventory') => 'Inventory',
            str_starts_with($path, 'api/v1/stock-movements') => 'Stock Movements',
            str_starts_with($path, 'api/v1/stock-adjustments') => 'Stock Adjustments',
            str_starts_with($path, 'api/v1/pos') => 'POS',
            str_starts_with($path, 'api/v1/purchases') => 'Purchases',
            str_starts_with($path, 'api/v1/sales') => 'Sales',
            str_starts_with($path, 'api/v1/reports') => 'Reports',
            str_starts_with($path, 'api/v1/dashboard') => 'Dashboard',
            str_starts_with($path, 'api/v1/activity-logs') => 'Activity Logs',
            default => 'General',
        };
    }
}
