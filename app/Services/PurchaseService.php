<?php

namespace App\Services;
use App\Models\PurchaseOrder;
class PurchaseService
{

    // get all purchase
    public function getPurchases()
    {
        $purchase  = PurchaseOrder ::all();

        return $purchase;
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
