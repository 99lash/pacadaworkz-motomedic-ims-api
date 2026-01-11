<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all permissions
        $permissions = Permission::all();

        // Get the superadmin role
        $superAdminRole = Role::where('role_name', 'superadmin')->first();

        // Assign all permissions to the superadmin role
        if ($superAdminRole) {
            $superAdminRole->permissions()->sync($permissions->pluck('id'));
        }
    }
}
