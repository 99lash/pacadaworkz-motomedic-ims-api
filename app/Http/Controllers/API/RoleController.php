<?php

namespace App\Http\Controllers\API;
use App\Services\RoleService;
use App\Http\Controllers\API\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    protected $roleService;
    public function  __construct(RoleService $roleService){
        $this->roleService = $roleService;
    }
     
 

    //show all roles
    public function index(){
         $roles = $this->roleService->getAllRoles();
         
        return response()->json([
            'success' => true,
            'data' => RoleResource::collection($roles)
        ]);
    }
    
    
//show by id
    public function show($id){
         $role = $this->roleService->getRoleById($id);

        if(!$role)
          return response()->json(['success'=>false, 'data' => ['error' => 'Not found']], 404);
        
         return response()->json(
           [
            "success" =>true,
            'data' => new RoleResource($role)
           ]
        );
    }

   

    //create new Role
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
   
      $validated = $validator->validated();

      
         $role = $this->roleService->create($validated);
         return response()->json([
             'success' => true,
             'data' => new RoleResource($role)
            ]);
    }



//update Role
   public function update(Request $request,$id){
      
     
     $validator = Validator::make($request->all(), [
    'description' => 'required'
     ]);

     if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'data' => $validator->errors()
    ], 400);
      }

      $validated = $validator->validated();
      $role = $this->roleService->update($validated,$id);

       if(!$role)
        return response()->json(['success'=>false, 'data' => ['error' => 'Bad Request']], 400);
  
     return response()->json([
         'success' => true,
         'data' => new RoleResource($role)
        ]);

   }




//delete role
   public function destroy($id){
        
      $deleted = $this->roleService->delete($id);

      if(!$deleted)
      return response()->json(['success'=>false, 'data' => ['error' => 'Role not found or could not be deleted']], 400);

     return response()->json([
         'success' => true,
         'data' => [
             'message' => 'Role deleted successfully'
         ]
        ]);
      
      
   }

}   
