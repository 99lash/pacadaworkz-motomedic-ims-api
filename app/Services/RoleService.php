<?php

namespace App\Services;
use App\Models\Role;

class RoleService{

    public function getAllRoles(){
        return Role::all();
    }
   
    public function getRoleById($id){
        
        return Role::findOrFail($id);
    }
   
   
    public function create(array $data)
    {
        return Role::create([
            'role_name' => $data['role_name'],
            'description' => $data['description']
        ]);
    }

     

    public function update(array $data,$id){
     
        $role = Role::findOrFail($id);
        
        $role->update($data);

        return $role;
    }



    public function delete($id)
    {
       $role = Role::findOrFail($id);
       
       return $role->delete();
    }

}
