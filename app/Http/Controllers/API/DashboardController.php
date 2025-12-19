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

    public function showStats(){
        try {
        
            $result = $this->dashboardService->getStats();

            return response()->json([
                'success' => true,
                'data' => $result
             ]);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showSalesTrend(){
        try {
            // Your logic here
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showTopProducts(){
        try {
            // Your logic here
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showRevenuePerCategory(){
        try {
            // Your logic here
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
