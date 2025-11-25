<?php

namespace App\Services;
use App\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleCollection;


class RoleService{
   private $roles;

   public function __construct(){
    $this->roles = Role::all(); 
   }

    public function getAllRoles(){
        return new RoleCollection($this->roles);
    }
   
    public function getRoleById($roleId){
        
        $role = $this->roles->findOrFail($roleId);
       
        return new RoleResource($role);
    }
   
   
    public function create(array $data)
    {
        $role = Role::create([
            'role_name' => $data['role_name'],
            'description' => $data['description']
        ]);
       

        return [
            'success' =>true,
            'data' => $data
        ];
    }

    

}
