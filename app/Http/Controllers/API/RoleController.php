<?php

namespace App\Http\Controllers\API;
use App\Services\RoleService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController
{
    protected $roleService;
    public function  __construct(RoleService $roleService){
        $this->roleService = $roleService;
    }
     

    public function index(){
         $roles = $this->roleService->getAllRoles();
         if(!$roles)
          return response()->json(["success"=>false, "data" => ["error" => "Bad Request"]], 400);

         
        return response()->json(
           $roles
        );
    }
    
    

    public function show($id){
        $roles = $this->roleService->getRoleById($id);

        if(!$roles)
           return response()->json(["success"=>false, "data" => ["error" => "Bad Request"]], 400);

         return response()->json(
           [
            "success" =>true,
            'data' => $roles
           ]
        );
    }
 
   

    public function store(Request $request){
        
        $validated = $request->validate([
            'role_name' =>'required|unique:roles,role_name|max:50',
            'description' =>'required'
        ]);
      
        

         $post = $this->roleService->create($validated);

         if(!$post)
         return response()->json(["success"=>false, "data" => ["error" => "Bad Request"]], 400);
         return response()->json($post);
    }

    
   public function update(){
    
   }

}   
