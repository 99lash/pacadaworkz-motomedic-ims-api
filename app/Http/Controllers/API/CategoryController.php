<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\API\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
class CategoryController extends Controller
{

   protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
         $this->categoryService = $categoryService;
    }


    public function index(Request $request){
       try{
                $search = $request->query('search',null);
               $result = $this->categoryService->getAllCategories($search);
        return response()->json([
            'success' =>true,
            'data' => CategoryResource::collection($result),
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

    public function store(CategoryRequest $request)
    {
        try {
        $result = $this->categoryService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($result)
        ], 201);

    }catch(\Exception $e){
         return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
    }
    }

   
    public function show($id){
        

        try{
             $result = $this->categoryService->getCategoryById($id);
            return response()->json([
                'success' => true,
                'data' => new CategoryResource($result)
            ]);

        }catch(\Exception $e){
               return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function update($id, CategoryRequest $request)
    {
        try{
           $result = $this->categoryService->update($id,$request->validated());

           return response()->json([
             'success'  => true,
             'data' => new CategoryResource($result)
           ]);

        }catch(\Exception $e){
                return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
       
    }

 
    public function destroy($id){
      
      try{
        $this->categoryService->delete($id);
          
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Role deleted successfully']
                ]);

      }catch(\Exception $e){
              
                   return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
      }

    }


}
