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
            ], 401);


    }


   }
  


}
