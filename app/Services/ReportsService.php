<?php

namespace App\Services;
use App\Models\SalesItem;
use App\Models\SalesTransaction;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
class ReportsService
{

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
    public function getProfitLossReport()
    {
        // will implement later
    }
}
