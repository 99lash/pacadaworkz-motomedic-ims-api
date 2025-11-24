<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        "password_hash" =>"superadmin",
        "first_name" => "Super",
        "last_name" => "admin"
            ],
            [
        "role_id" => 2,
        "name" => "Admin admin",
        "email" => "ashermanit48@gmail.com",
        "password_hash" =>"admin",
        "first_name" => "Admin",
        "last_name" => "admin"
            ],
           [
        "role_id" => 3,
        "name" => "Staff staff",
        "email" => "sharkspin@gmail.com",
        "password_hash" =>"staff",
        "first_name" => "Staff",
        "last_name" => "staff"
            ],

        ],
    
    
           );

    }


    //  protected $fillable = [
    //     'role_id',
    //     'name',
    //     'email',
    //     'password_hash',
    //     'first_name',
    //     'last_name'
    // ];

}
