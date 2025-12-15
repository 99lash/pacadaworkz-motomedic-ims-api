<?php

namespace App\Services;

use App\Models\StockAdjustment;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;

class StocksService
{
    /**
     * Retrieve and filter stock adjustments.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function showStockAdjustments(array $filters = []): LengthAwarePaginator
    {
        $query = StockAdjustment::query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['reason'])) {
            $query->where('reason', 'like', '%' . $filters['reason'] . '%');
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Find a specific stock adjustment by ID.
     *
     * @param int $id
     * @return StockAdjustment
     */
    public function showStockAdjustmentsById(int $id): StockAdjustment
    {
        return StockAdjustment::findOrFail($id);
    }

    /**
     * Export stock adjustment data.
     *
     * @param array $filters
     * @return mixed // Typically a file path or stream
     */
    public function exportStockAdjustments(array $filters = [])
    {
        // Logic to export stock adjustments based on filters
    }

    /**
     * Retrieve and filter stock movements.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getStockMovements(array $filters = []): LengthAwarePaginator
    {
        $query = StockMovement::with(['product.brand', 'user']); // Eager load product.brand as well

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['product_name'])) {
            $query->whereHas('product', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['product_name'] . '%');
            });
        }

        if (isset($filters['user_name'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['user_name'] . '%');
            });
        }

        // Add filtering by brand name
        if (isset($filters['brand_name'])) {
            $query->whereHas('product.brand', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['brand_name'] . '%');
            });
        }

        if (isset($filters['movement_type'])) {
            $query->where('movement_type', $filters['movement_type']);
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Find a specific stock movement by ID.
     *
     * @param int $id
     * @return StockMovement
     */
    public function showStockMovementsById(int $id): StockMovement
    {
        return StockMovement::findOrFail($id);
    }

    /**
     * Export stock movements data.
     *
     * @param array $filters
     * @return mixed // Typically a file path or stream
     */
    public function exportStockMovements(array $filters = [])
    {
        $query = StockMovement::with(['product.brand', 'user']);

        $movements = $query->get();

        $fileName = 'stock-movements-' . uniqid() . '.csv';
        $filePath = storage_path('app/private/' . $fileName);

        $handle = fopen($filePath, 'w');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Product Name',
            'Brand Name',
            'User Name',
            'Movement Type',
            'Quantity',
            'Created At',
        ]);

        // Add CSV rows
        foreach ($movements as $movement) {
            fputcsv($handle, [
                $movement->id,
                $movement->product->name,
                $movement->product->brand->name,
                $movement->user->name,
                $movement->movement_type,
                $movement->quantity,
                $movement->created_at,
            ]);
        }

        fclose($handle);

        return $filePath;
    }

    /**
     * Retrieve stock movements by product ID.
     *
     * @param int $productId
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getStockMovementsbyProductId(int $productId, array $filters = []): LengthAwarePaginator
    {
        $filters['product_id'] = $productId;
        return $this->getStockMovements($filters);
    }
}