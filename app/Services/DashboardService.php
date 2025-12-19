<?php
namespace App\Services;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\SalesItem;
use App\Models\Inventory;
use Carbon\Carbon;
class DashboardService{
    

    public function getStats(){
        $userCount = User::count();
        $productCount = Product::count();
        $transactionCount = SalesTransaction::count();
        $salesItem = SalesItem::count();
        $revenue = SalesTransaction::sum('subtotal');
        $lowstock = Inventory::where('quantity','<',10)->count();
       $outOfStock = Inventory::where('quantity', 0)->count();

        return [
          'total_products' => $productCount,
          'total_revenue' => $revenue,
          'total_transactions' => $transactionCount,
           'total_sales'=> $salesItem,
           'low_stock'=> $lowstock,
           'out_of_stock' => $outOfStock,
           'active_users' => $userCount
        ];


    }


      public function getSalesTrend(){
            $sales = [];

            for($i = 6; $i >= 0; $i--){
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                 $dateConvert = Carbon::now()->subDays($i)->format('M d');  
                $total = SalesTransaction::whereDate('created_at',$date)->sum('subtotal');

                $sales[$dateConvert] = $total;
            }



            return $sales;
        }
}