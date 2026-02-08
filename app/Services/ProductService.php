<?php
namespace App\Services;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\Services\ActivityLogService;

class ProductService{

  protected $activityLogService;

  public function __construct(ActivityLogService $activityLogService)
  {
      $this->activityLogService = $activityLogService;
  }



  // get all products
public function getAllProducts($search = null, $categoryId = null, $brandId = null)
{
    $query = Product::query()
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
        ->leftJoin('inventory', 'inventory.product_id', '=', 'products.id')
        ->select('products.*', 'inventory.quantity as current_stock');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('products.name', 'ILIKE', "%{$search}%")
              ->orWhere('products.description', 'ILIKE', "%{$search}%")
              ->orWhere('products.sku', 'ILIKE', "%{$search}%")
              ->orWhere('categories.name', 'ILIKE', "%{$search}%")
              ->orWhere('brands.name', 'ILIKE', "%{$search}%");
        });
    }

    if (!empty($categoryId)) {
        $query->where('products.category_id', $categoryId);
    }

    if (!empty($brandId)) {
        $query->where('products.brand_id', $brandId);
    }

    return $query->paginate(10)->withQueryString();
}

  
 //get products by if
  public function getProductById($id){

     return Product::findOrFail($id);
  }



  // create  products
      public function create(array $data){
   
        $product = Product::create([
         'category_id' => $data['category_id'],
         'brand_id' => $data['brand_id'],
          'sku' => $data['sku'],
          'name' => $data['name'],
          'description' => $data['description'] ?? null,
          'unit_price' => $data['unit_price'],
          'cost_price' => $data['cost_price'],
         'reorder_level' =>$data['reorder_level'] ?? 10,
        ]);
   
        // Log activity
        $this->activityLogService->log(
           module: 'Products',
           action: 'Create',
           description: "Created product: {$product->name} (SKU: {$product->sku})",
           userId: auth()->id()
        );
   
        return $product;
   
      }


//update product
   public function update(array $data, $id){

      $product = Product::findOrFail($id);
      $oldName = $product->name;

      $product->update($data);

      // Log activity
      $this->activityLogService->log(
         module: 'Products',
         action: 'Edit',
         description: "Updated product: {$oldName} to {$product->name} (SKU: {$product->sku})",
         userId: auth()->id()
      );

       return $product;

   }
//delete product
   public function delete($id)
   {

      $product = Product::findOrFail($id);
      $productName = $product->name;

      $result = $product->delete();

      if ($result) {
        // Log activity if deletion was successful
        $this->activityLogService->log(
           module: 'Products',
           action: 'Delete',
           description: "Deleted product: {$productName}",
           userId: auth()->id()
        );
      }

      return $result;
   }

   //create attribute in product

     public function createAttributeProduct(array $data,$id,$attributeId){
       
      $attribute = Attribute::findOrFail($attributeId);

      $product= Product::findOrFail($id); 
      $product_name = $product->name;
      if(!$attribute)
         return $attribute;
       else{
         $this->activityLogService->log(
           module: 'Products',
           action: 'Add attribute',
           description: "Add attribute to product:{$product_name}",
           userId: auth()->id()
        );
       }
      

      return ProductAttribute::updateOrCreate(
          ['product_id' => $id, 'attribute_value_id' => $data['attribute_value_id']],
          ['product_id' => $id, 'attribute_value_id' => $data['attribute_value_id']]
      );
      
      
     }


   //get all products for export
   public function getProductsForExport(){
       return Product::with(['category', 'brand'])->get();
   }


     //delete Attribute product

     public function deleteAttributeProduct($id,$attributeValueId){
          
        return ProductAttribute::where('product_id', $id)
                                ->where('attribute_value_id', $attributeValueId)
                                ->delete();

     }

}