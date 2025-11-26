<?php

namespace App\Services;
use App\Models\Role;

class RoleService{

    public function getAllRoles(){
        return Role::all();
    }
   
    public function getRoleById($id){
        
        return Role::find($id);
    }
   
   
    public function create(array $data)
    {
        return Role::create([
            'role_name' => $data['role_name'],
            'description' => $data['description']
        ]);
    }

     

    public function update(array $data,$id){
     
        $role = Role::find($id);
        if (!$role) {
            return null;
        }
        
        $role->description = $data['description'];
        
        $role->save();

        return $role;
    }



    public function delete($id)
    {
       $role = Role::find($id);
       if (!$role) {
           return false;
       }
       
       return $role->delete();
    }

}
