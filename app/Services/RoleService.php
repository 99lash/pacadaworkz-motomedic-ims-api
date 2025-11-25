<?php

namespace App\Services;
use App\Models\Role;


class RoleService{
  

    public function getAllRoles(){
      
        $roles = Role::all();

        return $roles;

    }
   
    // public function getRoleById($roleId){
        
    //     $role = Role::findby

    // }
   


}
