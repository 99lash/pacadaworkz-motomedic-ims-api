<?php
namespace App\Services;
use App\Models\Product;

class ProductService{

  public function getAllProducts($search = null,$filter = null){
    
    $query = Product::query();
    
    if ($search) 
     $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike',"%{$search}%");
    else if($filter)
    $query->where('category','Ilike',"%{$filter}%");
    

      return $query->paginate(10)->withQueryString();
  }


}