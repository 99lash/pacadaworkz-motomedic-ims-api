<?php

namespace App\Services;
use App\Models\PurchaseOrder;
class PurchaseService
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }
//get purchase service
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



// create purchase service
    public function createPurchase(array $data)
    {
        $purchase = PurchaseOrder::create($data);
        $this->activityLogService->log('Purchase', 'Create', "Created purchase order #{$purchase->id}");
        return $purchase;
    }

    public function findPurchase($id)
    {

        return PurchaseOrder::findOrFail($id);
    }


    //update purchase service
    public function updatePurchase($id, array $data)
    {
        $purchase = $this->findPurchase($id);
        $purchase->update($data);
        $this->activityLogService->log('Purchase', 'Update', "Updated purchase order #{$purchase->id}");
        return $purchase;
    }


  // delete purchase service
    public function deletePurchase($id)
    {
        $purchase = PurchaseOrder::findOrFail($id);
        $purchase->delete();
        $this->activityLogService->log('Purchase', 'Delete', "Deleted purchase order #{$purchase->id}");
        return true;
    }
}
