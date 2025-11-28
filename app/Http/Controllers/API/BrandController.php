<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\BrandResource;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController
{
    
   protected $brandService;

   public function __construct(BrandService $brandService){
      $this->brandService = $brandService;
   }



   public function index(Request $request){
    try{ 
         
        $search = $request->query('search',null);
        $result = $this->brandService->getAllBrands($search);
         
        return response()->json([
            'success' => true,
            'data' =>  BrandResource::collection($result),
             'meta' => [
                'current_page' => $result->currentPage(),
                'per_page' => $result->perPage(),
                 'total' => $result->total(),
                 'total_pages' => $result->lastPage()
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
