<?php
namespace App\Services;
use App\Models\Inventory;



class InventoryService{


  public function getAllInventory($search = null){
   

    $query = Inventory::query();

     if($search)
     {
        $query->join('products','products.id','inventory.product_id')->join('brands','brands.id','inventory.brand_id')
        ->where('products.name','ILIKE',"%{$search}%")->orwhere('brands.name','ILIKE',"%{$search}%");

        return $query->paginate(10)->withQueryString(); 
     }
  }




}