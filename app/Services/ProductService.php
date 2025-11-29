<?php
namespace App\Services;
use App\Models\Product;

class ProductService{

  public function getAllProducts($search = null,$filter = null){
    
    $query = Product::query();
    
    if ($search) 
     $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike',"%{$search}%")->orWhere('sku','Ilike',"%{$search}%");
    else if($filter)
 $query->join('categories', 'categories.id', '=', 'products.category_id')->join('brands','brands.id','=','products.brand_id')
      ->where('categories.name', 'ILIKE', "%{$filter}%")->orwhere('brands.name','ILike','%{%filter}%');

    

      return $query->paginate(10)->withQueryString();
  }


}