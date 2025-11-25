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
     

    public function show(){
         $roles = $this->roleService->getAllRoles();
         if(!$roles)
         {
         return response()->json(["success"=>false, "data" => ['error' => 'Invalid credentials']], 401);

         }

        return response()->json(
        [
            'success' => true,
             'data' => $roles
        ]
        );
    }

}
