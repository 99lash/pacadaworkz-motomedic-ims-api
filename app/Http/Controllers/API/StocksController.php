<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Http\Resources\StockMovementResource;
use App\Services\StocksService;

class StocksController extends Controller
{
    protected $stocksService;

    public function __construct(StocksService $stocksService)
    {
        $this->stocksService = $stocksService;
    }
    
      //Display a listing of the stock adjustments.
     
    public function showStockAdjustments(Request $request)
    {
        try {
            $adjustments = $this->stocksService->showStockAdjustments($request->all());
            return response()->json($adjustments);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

  // Display the specified stock adjustment.
    public function showStockAdjustmentsById(Request $request, $id)
    {
        try {
            $adjustment = $this->stocksService->showStockAdjustmentsById($id);
            return response()->json($adjustment);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Stock adjustment not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
 

     // Export stock adjustments to a file.
    public function exportStockAdjustments(Request $request)
    {
        try {
            $filePath = $this->stocksService->exportStockAdjustments($request->all());
            return response()->json(['message' => 'Stock adjustments exported successfully.', 'file_path' => $filePath]);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred during the export.'], 500);
        }
    }



  // show Stock movements
    public function showStockMovements(){
        try {
            $result = $this->stocksService->getStockMovements();
            return response()->json([
                'success' => true,
                'data' =>  StockMovementResource::collection($result)
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


 // show stock movements by id
 public function showStockMovementsById($id){
    try {
        $movement = $this->stocksService->showStockMovementsById($id);
        return response()->json($movement);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Stock movement not found.'], 404);
    } catch (Exception $e) {
        return response()->json(['message' => 'An unexpected error occurred.'], 500);
    }
 }


 //export stock movements
   public function exportStockMovements(){
    try {
        $filePath = $this->stocksService->exportStockMovements();
        return response()->json(['message' => 'Stock movements exported successfully.', 'file_path' => $filePath]);
    } catch (Exception $e) {
        return response()->json(['message' => 'An unexpected error occurred during the export.'], 500);
    }
   }

   //show stock movements by product id

   public function getStockMovementsbyProductId($productId){
    try {
        $movements = $this->stocksService->getStockMovementsbyProductId($productId);
        return response()->json($movements);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Product not found.'], 404);
    } catch (Exception $e) {
        return response()->json(['message' => 'An unexpected error occurred.'], 500);
    }
   }


}