<?php
namespace App\Services;
use App\Models\Product;

class ProductService{



  //get all products
  public function getAllProducts($search = null,$filter = null){
    
    $query = Product::query();
    
    if ($search) 
     $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike',"%{$search}%")->orWhere('sku','Ilike',"%{$search}%");
    else if($filter)
 $query->join('categories', 'categories.id', '=', 'products.category_id')->join('brands','brands.id','=','products.brand_id')
->where('categories.name', 'ILIKE', "%{$filter}%")->orwhere('brands.name','ILike',"%{$filter}%");

    

      return $query->paginate(10)->withQueryString();
  }
  
 //get products by if
  public function getProductById($id){

     return Product::findOrFail($id);
  }



  // create  products
   public function create(array $data){
       
     return Product::create([
      'category_id' => $data['category_id'],
      'brand_id' => $data['brand_id'],
       'sku' => $data['sku'],
       'name' => $data['name'],
       'description' => $data['description'] ?? null,
       'unit_price' => $data['unit_price'],
       'cost_price' => $data['cost_price'],
      'reorder_level' =>$data['reoder_level'] ?? 10,
     ]);
     
   }



//update product
   public function update(array $data, $id){
     
      $product = Product::findOrFail($id);

      $product->update($data);

       return $product;

   }

//delete product
   public function delete($id)
   {
     
      $product = Product::findOrFail($id);
       
      return $product->delete();
   }

}