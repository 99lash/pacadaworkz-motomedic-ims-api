<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Services\ReportsService;
use App\Http\Controllers\API\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
class ReportsController extends Controller
{
    protected $reportsService;

    public function __construct(ReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
    }
 
    // show all sales report
     public function showSalesReport(Request $request){
          try {
           
             $start = $request->query('start_date',null);
             $end  = $request->query('end_date',null);


             $result = $this->reportsService->getSalesReport($start,$end);
             return response()->json([
                'success' => true,
                'data' => $result
             ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Report not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
     }


     public function showPurchases(Request $request){
        try {
            $start = $request->query('start_date',null);
             $end  = $request->query('end_date',null);
   
             $result = $this->reportsService->getPurchases($start,$end);

             return response()->json([
                'success' => true,
                'data' => $result
             ]);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Report not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

     }

    public function getProfitLossReport()
    {
        try {
            // will implement later
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Report not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}