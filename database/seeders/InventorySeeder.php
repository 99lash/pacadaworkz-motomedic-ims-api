<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $timestamp = Carbon::now();

         $inventory = [
         [
          'product_id' => 1,
          'supplier_id' => 1,
          'quantity' => 20,
          'last_stock_in' => $timestamp,
          'created_at'  => $timestamp,
          'updated_at'  => $timestamp,
         ],
         [
            'product_id' => 2,
            'supplier_id' => 2,
            'quantity' => 15,
            'last_stock_in' => $timestamp,
            'created_at'  => $timestamp,
            'updated_at'  => $timestamp,
         ],
         [
            'product_id' => 3,
            'supplier_id' => 3,
            'quantity' => 30,
            'last_stock_in' => $timestamp,
            'created_at'  => $timestamp,
            'updated_at'  => $timestamp,
         ],
         [
            'product_id' => 4,
            'supplier_id' => 1,
            'quantity' => 25,
            'last_stock_in' => $timestamp,
            'created_at'  => $timestamp,
            'updated_at'  => $timestamp,
         ],
         [
            'product_id' => 5,
            'supplier_id' => 2,
            'quantity' => 10,
            'last_stock_in' => $timestamp,
            'created_at'  => $timestamp,
            'updated_at'  => $timestamp,
         ]

         ];

         DB::table('inventory')->insert($inventory);
    }
}
