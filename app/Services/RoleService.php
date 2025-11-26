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
   
    public function getRoleById($id){
        
        $role = $this->roles->find($id);
        if (!$role) return null;

        return new RoleResource($role);
    }
   
   
    public function create(array $data)
    {
    

        $role = Role::create([
            'role_name' => $data['role_name'],
            'description' => $data['description']
        ]);
       
        

        return  new RoleResource($role);
    }

     

    public function update(array $data,$id){
     
        $role = $this->roles->find($id);
        
        $role->description= $data['description'];
        
        $role->save();

          return [
            'success' =>true,
            'data' => new RoleResource($role)
        ];

    }



    public function delete($id)
    {
       $role = $this->roles->find($id);
       
       

       $role->delete();

       
          return [
            'success' =>true,
            'data' => [
                'message' =>'deleted successfully'
            ]
        ];
       

    }

}
