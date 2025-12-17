<?php

namespace App\Services;

use App\Models\StockAdjustment;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;

class StocksService
{
   
    //show stock adjustments service
  public function showStockAdjustments(?string $search = null, int $perPage = 15): LengthAwarePaginator
{
    $query = StockAdjustment::query();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('reason', 'LIKE', "%{$search}%")
              ->orWhere('user_id', 'LIKE', "%{$search}%")
              ->orWhereDate('created_at', $search);
        });
    }

    return $query->paginate($perPage)->withQueryString();
}

    // get specific stock adjustment
    public function showStockAdjustmentsById(int $id): StockAdjustment
    {
        return StockAdjustment::findOrFail($id);
    }

    // Export stock adjustment data.
    
    public function exportStockAdjustments()
    {
        $query = StockAdjustment::with('user');

        $adjustments = $query->get();

        $fileName = 'stock-adjustments-' . uniqid() . '.csv';
        $filePath = storage_path('app/private/' . $fileName);

        $handle = fopen($filePath, 'w');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'User Name',
            'Reason',
            'Description',
            'Created At',
        ]);

        // Add CSV rows
        foreach ($adjustments as $adjustment) {
            fputcsv($handle, [
                $adjustment->id,
                $adjustment->user->name,
                $adjustment->reason,
                $adjustment->description,
                $adjustment->created_at,
            ]);
        }

        fclose($handle);

        return $filePath;
    }

    
    //  Retrieve and filter stock movements.
     
  public function getStockMovements(?string $search = null, int $perPage = 15): LengthAwarePaginator
{
    $query = StockMovement::with(['product.brand', 'user']);

    if ($search) {
        $query->where(function ($q) use ($search) {

            // product name
            $q->whereHas('product', function ($p) use ($search) {
                $p->where('name', 'LIKE', "%{$search}%");
            })

            // brand name
            ->orWhereHas('product.brand', function ($b) use ($search) {
                $b->where('name', 'LIKE', "%{$search}%");
            })

            // user name
            ->orWhereHas('user', function ($u) use ($search) {
                $u->where('name', 'LIKE', "%{$search}%");
            })

            // movement type (in / out / adjustment)
            ->orWhere('movement_type', 'LIKE', "%{$search}%")

            // date search (YYYY-MM-DD)
            ->orWhereDate('created_at', $search);
        });
    }

    return $query->paginate($perPage)->withQueryString();
}


    // Find a specific stock movement by ID.
   
    public function showStockMovementsById(int $id): StockMovement
    {
        return StockMovement::findOrFail($id);
    }

    
     // Export stock movements data.
  
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

    // Retrieve stock movements by product ID.
    
    public function getStockMovementsbyProductId(int $productId, array $filters = []): LengthAwarePaginator
    {
        $filters['product_id'] = $productId;
        return $this->getStockMovements($filters);
    }
}