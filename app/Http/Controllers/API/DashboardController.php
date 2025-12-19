<?php

namespace App\Http\Controllers\API;
use App\Services\DashboardService;
use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;

class DashboardController
{

     protected $dashboardService;

     public function __construct(DashboardService $dashboardService){
        $this->dashboardService = $dashboardService;
     }

     //show statistics dashboard
    public function showStats(){
        try {
        
            $result = $this->dashboardService->getStats();

            return response()->json([
                'success' => true,
                'data' => $result
             ]);


        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occured'], 500);
        }
    }


    //show sales trend
    public function showSalesTrend(){
        try {
            $result = $this->dashboardService->getSalesTrend();
               
            return response()->json([
                'success' => true,
                 'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json(
                ['error' => 'An error occured'], 500);
        }
    }


    //show top products
    public function showTopProducts(){
        try {
        $result = $this->dashboardService->getTopProducts();

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
   

    // show revenue per category 
    public function showRevenueByCategory(){
        try {
            $result = $this->dashboardService->getRevenueByCategory();

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showInventoryOverview(){
        try {
            // Your logic here
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function showRecentActivities(){
        try {
            // Your logic here
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
