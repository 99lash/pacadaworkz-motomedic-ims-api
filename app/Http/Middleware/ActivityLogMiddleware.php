<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ActivityLogService;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
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
        ];

        // Skip excluded paths
        if ($request->is($excludedPaths)) {
            return $response;
        }

        // Skip plain GET requests without query params
        if ($request->isMethod('GET') && empty($request->query())) {
            return $response;
        }

        // Only log authenticated users
        if (! auth()->check()) {
            return $response;
        }

        $method = $request->method();

        //determine module name
        $segments = $request->segments();
        $lastSegment = end($segments);

        // If last segment is numeric (ID), use previous segment as module
        $module = is_numeric($lastSegment)
            ? prev($segments)
            : $lastSegment;

      //Determine Action and desciption
        $action = $this->mapAction($request->method());
        $description = ucfirst($action) . ' ' . $module;

        // Handle search / filter logging
        if ($request->isMethod('GET') && ! empty($request->query())) {

            $action = 'Filter/Search';

            $searchDetails = collect($request->query())
                ->except(['password', 'token']) // safety
                ->map(fn ($value, $key) => "$key = $value")
                ->implode(', ');

            $description = "Searched {$module} with {$searchDetails}";
        }
        

        
     if ($method === 'POST') {

            // $data = collect($request->all())
            //     ->except(['password', 'token'])
            //     ->map(fn ($value, $key) => "$key = $value")
            //     ->implode(', ');

                $data = $request->input('name');


            $description = "Created {$module} : {$data}";
        }
              

                if (in_array($method, ['PUT', 'PATCH'])) {

            $data = collect($request->all())
                ->except(['password', 'token'])
                ->map(fn ($value, $key) => "$key = $value")
                ->implode(', ');

            $id = is_numeric($lastSegment) ? $lastSegment : 'unknown';

            $description = "Updated {$module} ID {$id} with {$data}";
        }


                if ($method === 'DELETE') {

            $id = is_numeric($lastSegment) ? $lastSegment : 'unknown';

            $description = "Deleted {$module} ID {$id}";
        }



       // save as activity log
        app(ActivityLogService::class)->log(
            module: $module,
            action: $action,
            description: $description
        );

        return $response;
    }

   // map http actions

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


private function detectModule(string $path): string
{
    return match (true) {

        // Authentication
        str_starts_with($path, 'api/v1/auth') =>
            'Authentication',

        // Users & Access Control
        str_starts_with($path, 'api/v1/users') =>
            'Users',
        str_starts_with($path, 'api/v1/roles') =>
            'Roles',
        str_starts_with($path, 'api/v1/permissions') =>
            'Permissions',

        // Master Data
        str_starts_with($path, 'api/v1/categories') =>
            'Categories',
        str_starts_with($path, 'api/v1/brands') =>
            'Brands',
        str_starts_with($path, 'api/v1/attributes') =>
            'Attributes',
        str_starts_with($path, 'api/v1/products') =>
            'Products',
        str_starts_with($path, 'api/v1/suppliers') =>
            'Suppliers',

        // Inventory & Stocks
        str_starts_with($path, 'api/v1/inventory') =>
            'Inventory',
        str_starts_with($path, 'api/v1/stock-movements') =>
            'Stock Movements',
        str_starts_with($path, 'api/v1/stock-adjustments') =>
            'Stock Adjustments',

        // POS & Transactions
        str_starts_with($path, 'api/v1/pos') =>
            'POS',
        str_starts_with($path, 'api/v1/purchases') =>
            'Purchases',
        str_starts_with($path, 'api/v1/sales') =>
            'Sales',

        // Reports & Dashboard
        str_starts_with($path, 'api/v1/reports') =>
            'Reports',
        str_starts_with($path, 'api/v1/dashboard') =>
            'Dashboard',

        // Activity Logs
        str_starts_with($path, 'api/v1/activity-logs') =>
            'Activity Logs',

        default =>
            'General',
    };
}

}
