<?php
namespace App\Services;
use App\Models\RolePermission;
use App\Models\Role;
class RolePermissionService{

public function assignPermissions($roleId, array $permissions)
{
    $role = Role::find($roleId);

    if (! $role) {
        return [
            'success' => false,
            'message' => 'Role not found.'
        ];
    }

    $role->permissions()->sync($permissions);


    $role->load('permissions');

    return [
        'success' => true,
        'message' => 'Permissions assigned successfully.',
         'role_name'=> $role->role_name,
        'permissions' => $role->permissions()->get(),
    ];
}

  
    
}