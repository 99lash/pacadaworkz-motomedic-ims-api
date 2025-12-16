<?php

namespace App\Services;
use App\Models\PurchaseOrder;
class PurchaseService
{

public function getPurchases($search = null)
{
    $query = PurchaseOrder::with(['supplier', 'user']);

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {

            // Supplier match
            $q->whereHas('supplier', function ($supplierQuery) use ($search) {
                $supplierQuery->where('name', 'LIKE', "%{$search}%");
            })

            // OR User match
            ->orWhereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%");
            })

            // OR PurchaseOrder fields
            ->orWhere(function ($purchaseQuery) use ($search) {
                $purchaseQuery->where('status', 'LIKE', "%{$search}%")
                              ->orWhere('notes', 'LIKE', "%{$search}%")
                              ->orWhere('total_amount', 'LIKE', "%{$search}%");
            });
        });
    }

    return $query->paginate(10)->withQueryString();
}




    public function createPurchase(array $data)
    {
        //
    }

    public function findPurchase($id)
    {
        //
    }

    public function updatePurchase($id, array $data)
    {
        //
    }

    public function deletePurchase($id)
    {
        //
    }
}
