<?php

namespace App\Http\Controllers\API;
use  App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{

   protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
         $this->categoryService = $categoryService;
    }


    public function index(){

        $result = $this->categoryService->getAll();



        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($result)
        ]);


    }

    public function store(CategoryRequest $request)
    {
        $result = $this->categoryService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($result)
        ], 201);
    }



}
