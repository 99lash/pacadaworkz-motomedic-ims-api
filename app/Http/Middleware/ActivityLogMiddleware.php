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
            'api/v1/pos/checkout'

        ];

        // Skip excluded paths
        if ($request->is($excludedPaths)) {
            return $response;
        }


         //determine module name
        $segments = $request->segments();
        $lastSegment = end($segments);

        // Skip plain GET requests without query params
       if (
    $request->isMethod('GET')
    && empty($request->query())
    && $lastSegment !== 'export'
) {
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

        $description = $this->specialDescriptionHolder($module,$request, $id, $last);

        if($description)
            return $description;

        if ($request->isMethod('GET') && !empty($request->query())) {
            $query = collect($request->query())
                ->except(['password', 'token'])
                ->map(fn ($v, $k) => "$k=$v")
                ->implode(', ');
            return "Searched/filtered {$module}" . ($query ? " with: {$query}" : "");
        }

      return false;
    }







    // roles description
    private function buildRolesDescription(Request $request, ?string $id, string $last): string
    {
        $roleId = $request->route('role') ?? $id;
        $roleName = null;

        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $roleName = $role->role_name;
            }
        }

        $roleIdentifier = $roleName ? "'{$roleName}'" : ($roleId ? "#{$roleId}" : "");

        if ($request->isMethod('POST') && $last === 'permissions') {
            return "Assigned permissions to role {$roleIdentifier}";
        }

        return match ($request->method()) {
            'POST'   => 'Created a new role',
            'PUT'    => "Updated role {$roleIdentifier}",
            'DELETE' => "Deleted role {$roleIdentifier}",
            default  => "Viewed role {$roleIdentifier}",
        };
    }



    //stock movement
private function buildStockMovementsDescription(Request $request, ?string $id, string $last): string
{
    if ($request->isMethod('GET')) {
        if ($last === 'export') {
            return "Exported stock movements to CSV";
        }
//ed
        if (!empty($request->query())) {
            $query = collect($request->query())
                ->except(['password', 'token'])
                ->map(fn ($v, $k) => str_replace('_', ' ', $k) . "=" . $v)
                ->implode(', ');
            return "Searched/filtered stock movements" . ($query ? " with: {$query}" : "");
        }

        return "Viewed stock movement" . ($id ? " #{$id}" : "");
    }

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




// helper function that helps to hold descriptions dynamically
private function specialDescriptionHolder($module,$request, $id, $last){

  return match(true){
    $module === 'Roles' => $this->buildRolesDescription($request, $id, $last),
    $module === 'Stock Movements' => $this->buildStockMovementsDescription($request, $id, $last),
    $module === 'Stock Adjustments' => $this->buildStockAdjustmentsDescription($request, $id, $last),
    default => false
  };



}

}

/*

Mga nagawan ko na ng customized buildDescription
1.sales
2.POS
3.Roles
4.Stock-movements
5.Stock-adjustments

*/
