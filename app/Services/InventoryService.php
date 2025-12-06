<?php
namespace App\Services;
use App\Models\Inventory;



class InventoryService{


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


  public function getInventoryById($id){}


}