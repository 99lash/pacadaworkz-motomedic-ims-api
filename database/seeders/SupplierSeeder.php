<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


         $timestamp = Carbon::now();
        
     $suppliers= [
     [
        'name' => 'Honda Auto Parts Supply',
        'contact_person' => 'Maria Santos',
        'email' => 'maria@hondaparts.ph',
         'phone' => '+63 917 123 4567',
         'address' => 'Manila,Philippines',
         'created_at'  => $timestamp,
         'updated_at'  => $timestamp,
     ],
     [
        'name' => 'Toyota Car Parts Inc.',
        'contact_person' => 'John Doe',
        'email' => 'john.doe@toyotacars.ph',
         'phone' => '+63 918 987 6543',
         'address' => 'Quezon City, Philippines',
         'created_at'  => $timestamp,
        'updated_at'  => $timestamp,
     ],
     [
        'name' => 'Mitsubishi Motors Parts',
        'contact_person' => 'Jane Smith',
        'email' => 'jane.smith@mitsubishiparts.ph',
         'phone' => '+63 919 111 2222',
         'address' => 'Cebu City, Philippines',
         'created_at'  => $timestamp,
        'updated_at'  => $timestamp,
     ]

     ];
       DB::table('suppliers')->insert($suppliers);
    }
}
