<?php
namespace App\Services;
use App\Models\Inventory;



class InventoryService
{
    public function getAllInventory($search = null)
    {
        $query = Inventory::with(['product.brand', 'product.category']);

        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhereHas('brand', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%");
                    });
            });
        }

        return $query->paginate(10)->withQueryString();
    }

    public function getInventoryById($id)
    {
        $inventory = Inventory::findOrFail($id);

        return $inventory;
    }

    public function createInventory(array $data)
    {
        return Inventory::create($data);
    }

    public function updateInventory(array $data, $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->update($data);

        return $inventory;
    }

    public function deleteInventory($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return true;
    }
}