<?php

namespace App\Services;
use App\Models\SalesItem;
use App\Models\SalesTransaction;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsService
{
 // sales report
    public function getSalesReport($start = null,$end = null){
   

    $start = $start ?? Carbon::now()->format('Y-m-d');
$end = $end ?? Carbon::now()->format('Y-m-d');


        $query = SalesTransaction::query();
         $trend = SalesTransaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
         ->when($start && $end, function($q) use ($start , $end){
          $q->whereBetween('created_at',[$start,$end]);
         })
         ->groupBy('date')
         ->orderBy('date')
         ->get();

      if($start && $end){
        $query->whereBetween('created_at',[$start,$end]);
      }

    // return $totalSales->sum('total_amount');

     return [
        'total_sales' => $query->sum('total_amount'),
        'transactions' => $query->count(),
        'average_transaction' => $query->avg('total_amount'),
        'trend' => $trend
     ];


    }


// purchase report 
    public function getPurchases($start = null, $end = null){
    $start = $start ?? Carbon::now()->format('Y-m-d');
    $end = $end ?? Carbon::now()->format('Y-m-d');

  //  $itemsQuery = PurchaseItem::query(); baka magamit soon
    $ordersQuery = PurchaseOrder::query();
    $ordersQuery->whereBetween('created_at',[$start,$end]);
    $averageOrder = $ordersQuery->avg('total_amount') ?? 0;

    $trend = PurchaseOrder::selectRaw('DATE(created_at) as date, SUM(total_amount)as total')
    ->when($start && $end, function($x) use($start,$end){ 
      $x->whereBetween('created_at',[$start,$end]);
    })
    ->groupBy('date')
         ->orderBy('date')
         ->get();

    return [
       'total_purchases' => $ordersQuery->sum('total_amount'),
       'purchase_orders' => $ordersQuery->count(),
       'average_orders' => $averageOrder,
       'trend' => $trend
    ];
    }

//inventory report 
    public function getInventory($start = null, $end = null){
         $start = $start ?? Carbon::now()->format('Y-m-d');
         $end = $end ?? Carbon::now()->format('Y-m-d');
   // query of product
         $productsQuery = Product::query();
  //inventory
  $inventory = DB::table('inventory');
// inventory value
     $totalInventoryValue = $inventory
          ->join('products','inventory.product_id','=','products.id')
          ->select(DB::raw('SUM(products.cost_price * inventory.quantity) as total_value'))
          ->value('total_value');     

// low stock
 $lowStock = $inventory->where('quantity','<',10)->count();
// no stock
 $noStock =  $inventory->where('quantity','=', 0)->count();

 //product distribution by category
 $distCategory= DB::table('categories as a')
    ->join('products as b', 'b.category_id', '=', 'a.id')
    ->select('a.name', DB::raw('COUNT(b.category_id) as total'))
    ->groupBy('a.name')
    ->get();
// inventory value by category
  $valCategory = DB::table('categories as a')
   ->join('products as b', 'b.category_id','=','a.id')
   ->join('inventory as c', 'c.product_id','=','b.id')
   ->select('a.name',DB::raw('SUM(c.quantity * b.unit_price) as inventory_value'))
   ->groupBy('a.name')
   ->get();
         return [
          'total_products' => $productsQuery->count(),
          'total_value' => $totalInventoryValue,
           'low_stock' => $lowStock,
           'out_of_stock' => $noStock,
           'ditribution_category' => $distCategory,
           'inventory_value_category' => $valCategory
         ];

    }


    // get Performance

     public function getPerformance($start = null, $end = null){
     if (!isset($start)) {
    $start = DB::table('sales_items')->min('created_at'); // returns earliest datetime or null
    $start = $start ? Carbon::parse($start)->startOfDay() : Carbon::today()->startOfDay();
     }

// Kung walang $end, default today
$end = $end ?? Carbon::today()->endOfDay();
      $revenueByCategory = DB::table('categories as a')
      ->leftJoin('products as b' , 'a.id', '=', 'b.category_id')
      ->leftJoin('sales_items as c', function($join) use ($start,$end){
        $join->on('b.id','=','c.product_id')
             ->whereBetween('c.created_at',[$start,$end]);
      }) ->select(
        'a.name',
        DB::raw('COALESCE(SUM(c.unit_price * c.quantity), 0) as total')
    )
    ->groupBy('a.name')
    ->orderBy('a.name')
    ->get();
 

   $revenueByBrand = DB::table('brands as a')
      ->leftJoin('products as b' , 'a.id', '=', 'b.brand_id')
      ->leftJoin('sales_items as c', function($join) use ($start,$end){
        $join->on('b.id','=','c.product_id')
             ->whereBetween('c.created_at',[$start,$end]);
      }) ->select(
        'a.name',
        DB::raw('COALESCE(SUM(c.unit_price * c.quantity), 0) as total')
    )
    ->groupBy('a.name')
    ->orderBy('a.name')
    ->get();

return [
  'revenue_by_category' => $revenueByCategory,
  'revenue_by_brand' => $revenueByBrand
];
       
     }



public function getStockAdjustments($start = null, $end = null){

    if (!isset($start)) {
    $start = DB::table('sales_items')->min('created_at'); // returns earliest datetime or null
    $start = $start ? Carbon::parse($start)->startOfDay() : Carbon::today()->startOfDay();
     }
// Kung walang $end, default today
   $end = $end ?? Carbon::today()->endOfDay();
//total adjustment count
$totalAdjustments = DB::table('stock_adjustments')->whereBetween('created_at',[$start,$end])->count();

// adjustment value
 $adjustmentValue = DB::table('stock_adjustments as a')
    ->join('stock_movements as b', 'a.id', '=', 'b.reference_id')
    ->join('products as c', 'c.id', '=', 'b.product_id')
    ->where('b.reference_type', 'adjustment')
    ->select(DB::raw("
        SUM(
            CASE
                WHEN b.movement_type = 'in' THEN b.quantity * c.cost_price
                WHEN b.movement_type = 'out' THEN -b.quantity * c.cost_price
            END
        ) as adjustment_value
    "))
    ->value('adjustment_value');
//reasons
$reasonCounts = DB::table('stock_adjustments')
    ->select('reason', DB::raw('COUNT(reason) as num_reasons'))
    ->groupBy('reason')
    ->get();

return [
  'total_adjustments' => $totalAdjustments,
  'adjustments_value' => $adjustmentValue,
  'adjustments_by_reason' => $reasonCounts
];
}
    public function getProfitLossReport()
    {
        // will implement later
    }
}
