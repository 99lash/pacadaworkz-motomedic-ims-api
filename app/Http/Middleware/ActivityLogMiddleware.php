<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ActivityLogService;

class ActivityLogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Execute controller first
        $response = $next($request);

        // Excluded paths (handled manually)
        $excludedPaths = [
            'api/v1/auth/login',
            'api/v1/auth/logout',
            'api/v1/pos/checkout',
            'api/v1/roles'

        ];

        // Skip excluded paths
        if ($request->is($excludedPaths)) {
            return $response;
        }


         //determine module name
        $segments = $request->segments();
        $lastSegment = end($segments);

                // Skip plain GET requests without query params, unless it's an export
                if ($request->isMethod('GET') && empty($request->query()) && $lastSegment !== 'export') {
                    return $response;
                }
        // Only log authenticated users
        if (!auth()->check()) {
            return $response;
        }

        $method = $request->method();
        $path   = $request->path();

        $module = $this->detectModule($path);
        $action = $this->mapAction($method);

        $description = $this->buildDescription($request, $module, $action,$response);

        if($description != false){
        //  Correct: instance-based service call
        app(ActivityLogService::class)->log(
            module: $module,
            action: $action,
            description: $description,
            userId: auth()->id()
        );
        }
        return $response;
    }

    /* =========================
     * ACTION MAPPING
     * ========================= */
    private function mapAction(string $method): string
    {
        return match ($method) {
            'GET'    => 'View',
            'POST'   => 'Create',
            'PUT',
            'PATCH'  => 'Edit',
            'DELETE' => 'Delete',
            default  => 'Performed',
        };
    }

    /* =========================
     * MODULE DETECTION
     * ========================= */
    private function detectModule(string $path): string
    {
        return match (true) {

            // Authentication
            str_starts_with($path, 'api/v1/auth') => 'Authentication',

            // Users & Access Control
            str_starts_with($path, 'api/v1/users') => 'Users',
            str_starts_with($path, 'api/v1/roles') => 'Roles',
            str_starts_with($path, 'api/v1/permissions') => 'Permissions',

            // Master Data
            str_starts_with($path, 'api/v1/categories') => 'Categories',
            str_starts_with($path, 'api/v1/brands') => 'Brands',
            str_starts_with($path, 'api/v1/attributes') => 'Attributes',
            str_starts_with($path, 'api/v1/products') => 'Products',
            str_starts_with($path, 'api/v1/suppliers') => 'Suppliers',

            // Inventory
            str_starts_with($path, 'api/v1/inventory') => 'Inventory',
            str_starts_with($path, 'api/v1/stock-movements') => 'Stock Movements',
            str_starts_with($path, 'api/v1/stock-adjustments') => 'Stock Adjustments',

            // POS
            str_starts_with($path, 'api/v1/pos') => 'POS',

            // Transactions
            str_starts_with($path, 'api/v1/purchases') => 'Purchases',
            str_starts_with($path, 'api/v1/sales') => 'Sales',

            // Reports
            str_starts_with($path, 'api/v1/reports') => 'Reports',
            str_starts_with($path, 'api/v1/dashboard') => 'Dashboard',
            str_starts_with($path, 'api/v1/activity-logs') => 'Activity Logs',

            default => 'General',
        };
    }

    /* =========================
     * DESCRIPTION BUILDER
     * ========================= */
    private function buildDescription(Request $request, string $module, string $action): string
    {
        $segments = $request->segments();
        $last     = end($segments);
        $id       = is_numeric($last) ? $last : null;

        if ($request->isMethod('GET')) {
            if ($last === 'export') {
                return "Export {$module}";
            }
            // If it's a GET request and not an export, then check for query params for searching/filtering
            if (!empty($request->query())) {
                $query = collect($request->query())
                    ->except(['password', 'token'])
                    ->map(fn ($v, $k) => "$k=$v")
                    ->implode(', ');
                return "Searched/filtered {$module}" . ($query ? " with: {$query}" : "");
            }
            // If it's a GET request, not an export, and no query params, return false to skip logging.
            return false;
        }

        // Return false for non-GET requests if no specific description logic is provided
        return false;
    }










private function buildStockAdjustmentsDescription(Request $request, ?string $id, string $last): string
{


if ($request->isMethod('GET')) {
        if ($last === 'export') {
            return "Exported stock adjustments to CSV";
        }

}else if($request->isMethod('POST')){
       $reason = $request->input('reason');
        return "stock adjusted, reason : {$reason}";
}else if (in_array($request->method(), ['PUT', 'PATCH'])) {

$description = "Updated stock adjustment #{$id}";

    if ($request->filled('reason')) {
        $description .= " | reason: " . $request->input('reason');
    }

    if ($request->filled('notes')) {
        $description .= " | notes: " . $request->input('notes');
    }

    return $description;
}
 $query = collect($request->query())
                ->except(['password', 'token'])
                ->map(fn ($v, $k) => str_replace('_', ' ', $k) . "=" . $v)
                ->implode(', ');
return "filtered/search stock adjustments {$query}";

}





}

