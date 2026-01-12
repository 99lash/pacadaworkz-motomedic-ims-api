<?php 
namespace App\Services;
use App\Models\Brand;
class BrandService{

//get all brands
public function getAllBrands($search = null, $perPage = 10){
   
    $query = Brand::query();

    if($search)
    {
        $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike','%{$search}%');
    }


    return $query->paginate($perPage)->withQueryString();

}

//get brands by id
public function getBrandById($id){
    
    return Brand::findOrFail($id);

}

//create new brand
public function create(array $data){
   
    $brand = Brand::create($data);

    return $brand;


}


//update brand

public function update(array $data, $id){
     
    $brand = Brand::findOrFail($id);
    

    $brand->update($data);
    
    
    return $brand;

}


//delete brand

public function delete($id){
  
    $brand = Brand::findOrFail($id);
    
    return $brand->delete();

}

}