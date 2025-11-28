<?php 
namespace App\Services;
use App\Models\Brand;
class BrandService{


public function getAllBrands($search = null){
   
    $query = Brand::query();

    if($search)
    {
        $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike','%{$search}%');
    }


    return $query->paginate(10)->withQueryString();

}

}