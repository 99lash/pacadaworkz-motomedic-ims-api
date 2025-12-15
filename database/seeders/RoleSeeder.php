<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'superadmin',
                'description' => 'Administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'admin',
                'description' => 'Administrator with access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
               [
                'role_name' => 'staff',
                'description' => 'Staff with limited permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
