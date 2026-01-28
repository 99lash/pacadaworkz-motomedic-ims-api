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
        if (!auth()->check()) {
            return $response;
        }

        $method = $request->method();
        $path   = $request->path();

        $module = $this->detectModule($path);
        $action = $this->mapAction($method);

        $description = $this->buildDescription($request, $module, $action);

        // ✅ Correct: instance-based service call
        app(ActivityLogService::class)->log(
            module: $module,
            action: $action,
            description: $description,
            userId: auth()->id()
        );

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

        if ($module === 'POS') {
            return $this->buildPosDescription($request, $id, $last);
        }

        if ($module === 'Sales') {
            return $this->buildSalesDescription($request, $id, $last);
        }

        if ($request->isMethod('GET') && !empty($request->query())) {
            $query = collect($request->query())
                ->except(['password', 'token'])
                ->map(fn ($v, $k) => "$k=$v")
                ->implode(', ');
            return "Searched/filtered {$module}" . ($query ? " with: {$query}" : "");
        }

        return match ($action) {
            'Create' => "Created a new {$module}",
            'Edit'   => "Updated {$module}" . ($id ? " #{$id}" : ""),
            'Delete' => "Deleted {$module}" . ($id ? " #{$id}" : ""),
            default  => "Viewed {$module}",
        };
    }

    /* =========================
     * POS DESCRIPTION HANDLER
     * ========================= */
    private function buildPosDescription(Request $request, ?string $id, string $last): string
    {
        if ($request->isMethod('POST')) {
            return match ($last) {
                'add-item'       => 'Added item to cart',
                'clear'          => 'Cleared the cart',
                'apply-discount' => 'Applied discount to cart',
                'checkout'       => 'Checked out the cart',
                default          => 'Performed POS action',
            };
        }

        if ($request->isMethod('PATCH')) {
            return $id
                ? "Updated item #{$id} in cart"
                : "Updated cart item";
        }

        if ($request->isMethod('DELETE')) {
            return $id
                ? "Removed item #{$id} from cart"
                : "Removed item from cart";
        }

        return 'Viewed POS';
    }


    private function buildSalesDescription(Request $request, ?string $id, string $last): string
    {
        if ($request->isMethod('POST')) {
            return match ($last) {
                'void'   => "Voided sales transaction" . ($id ? " #{$id}" : ""),
                'refund' => "Refunded sales transaction" . ($id ? " #{$id}" : ""),
                default  => 'Created sales transaction',
            };
        }

        if ($request->isMethod('GET')) {
            if ($last === 'receipt') {
                return "Viewed sales receipt" . ($id ? " for transaction #{$id}" : "");
            }

            if (!empty($request->query())) {
                $query = collect($request->query())
                    ->except(['password', 'token'])
                    ->map(fn ($v, $k) => str_replace('_', ' ', $k) . "=" . $v)
                    ->implode(', ');
                return "Searched/filtered sales transaction" . ($query ? " with: {$query}" : "");
            }

            return "Viewed sales transaction" . ($id ? " #{$id}" : "");
        }

        return 'Performed sales action';
    }
}
