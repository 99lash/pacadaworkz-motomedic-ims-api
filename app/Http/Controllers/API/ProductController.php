<?php

namespace App\Http\Controllers\API;

use App\Services\ProductService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class ProductController
{


    protected $productService;

   public function __construct(ProductService $productService){
      $this->productService = $productService;
   }

   //get all products
   public function index(Request $request){
     
    try{
    $search = $request->query('search',null);
    $filter = $request->query('filter',null);   
     
    $result = $this->productService->getAllProducts($search,$filter);
     return response()->json([
        'success' => true,
        'data' => ProductResource::collection($result),
        'meta' => [
        'current_page' => $result->currentPage(),
        'per_page' => $result->perPage(),
        'total' => $result->total(),
         'last_page' => $result->lastPage()
        ]
     ]);
     
    }catch(\Exception $e){
         return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);


    }


   }
  

   //get product by id

   public function show($id){


      try{
       
         $result = $this->productService->getProductById($id);

         return response()->json([
           'success'  => true,
            'data' => new ProductResource($result)
         ] 
         );

      }catch(ModelNotFoundException $e){
        return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
      }catch(\Exception $e){
                 return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

      }



   }


   //store product

      public function store(ProductRequest $request){

         
      try{
       
         $result = $this->productService->create($request->validated());

         return response()->json([
           'success'  => true,
            'data' => new ProductResource($result)
         ] 
         );

      }catch(ModelNotFoundException $e){
        return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
      }catch(\Exception $e){
                 return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

      }
      }



//update product
      public function update(ProductRequest $request, $id){
     
              
      try{
         

         $result = $this->productService->update($request->validated(),$id);

         return response()->json([
           'success'  => true,
            'data' => new ProductResource($result)
         ] 
         );

      }catch(ModelNotFoundException $e){
        return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
      }catch(\Exception $e){
                 return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

      }

      }


//delete product 
      

public function destroy($id){

         
      try{
       
         $result = $this->productService->delete($id);

           return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Product deleted successfully']
                ]);


      }catch(ModelNotFoundException $e){
        return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
      }catch(\Exception $e){
                 return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

      }
}



}
