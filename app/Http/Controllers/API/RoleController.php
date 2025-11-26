<?php

namespace App\Http\Controllers\API;
use App\Services\RoleService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class RoleController
{
    protected $roleService;
    public function  __construct(RoleService $roleService){
        $this->roleService = $roleService;
    }
     

    public function index(){
         $roles = $this->roleService->getAllRoles();
         if(!$roles)
          return response()->json(['success'=>false, 'data' => ['error' => 'Bad Request']], 400);

         
        return response()->json(
           $roles
        );
    }
    
    

    public function show($id){
         $roles = $this->roleService->getRoleById($id);

        if(!$roles)
          return response()->json(['success'=>false, 'data' => ['error' => 'Not found']], 404);
        
         return response()->json(
           [
            "success" =>true,
            'data' => $roles
           ]
        );
    }

   

    public function store(Request $request){
        
     $validator = Validator::make($request->all(), [
        'role_name' => 'required|unique:roles,role_name|max:50',
        'description' => 'required'
    ]);

         

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'data' => $validator->errors()
        ], 400);
    }

         $post = $this->roleService->create($validatpr);

        

         return response()->json(['success' =>'true','data' => $post]);
    }

    
   public function update(Request $request,$id){
      
      $validated = $request->validate([
         'description' =>'required'
      ]);


      $update = $this->roleService->update($validated,$id);

       if(!$update)
        return response()->json(['success'=>false, 'data' => ['error' => 'Bad Request']], 400);


     
     return response()->json($update);

   }


   public function destroy($id){
        
      $deleted = $this->roleService->delete($id);

      if(!$deleted)
      return response()->json(['success'=>false, 'data' => ['error' => 'Bad Request']], 400);

     return response()->json($deleted);
      
      
   }

}   
