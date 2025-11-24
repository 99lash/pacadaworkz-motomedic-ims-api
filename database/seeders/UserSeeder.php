<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                "role_id" => 1,
                "name" => "Super admin",
                "email" => "domdomkenneth23@gmail.com",
                "password" => Hash::make("superadmin"),
                "first_name" => "Super",
                "last_name" => "admin"
            ],
            [
                "role_id" => 2,
                "name" => "Admin admin",
                "email" => "ashermanit48@gmail.com",
                "password" => Hash::make("admin"),
                "first_name" => "Admin",
                "last_name" => "admin"
            ],
            [
                "role_id" => 3,
                "name" => "Staff staff",
                "email" => "sharkspin@gmail.com",
                "password" => Hash::make("staff"),
                "first_name" => "Staff",
                "last_name" => "staff"
            ],
        ]);
    }
}
