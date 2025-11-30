<?php
namespace App\Http\Controllers\API;

use App\Services\AttributeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AttributeResource;
use App\Http\Requests\AttributeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttributeController
{
    

    protected $attributeService;
    public function __construct(AttributeService $attributeService){
        $this->attributeService = $attributeService;
    }

   //show all attributes
    public function index(Request $request)
    {


          try{
                $search = $request->query('search',null);
               $result = $this->attributeService->getAllAttributes($search);
        return response()->json([
            'success' =>true,
            'data' => AttributeResource::collection($result),
            'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'total_pages' => $result->lastPage(),
                ],
        ]);
       }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
       }

    }

    //show attribute by id
    public function show($id){
        
        try{
             $result = $this->attributeService->getAttributeById($id);
            return response()->json([
                'success' => true,
                'data' => new AttributeResource($result)
            ]);

        }catch(\Exception $e){
               return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }




// create new Attribute
    public function store(AttributeRequest $request){

       try {
        $result = $this->attributeService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new AttributeResource($result)
        ], 201);

    }catch(\Exception $e){
         return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
    }
    }


    

}
