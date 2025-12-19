<?php
namespace App\Services;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\SalesItem;
use App\Models\Inventory;
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
}