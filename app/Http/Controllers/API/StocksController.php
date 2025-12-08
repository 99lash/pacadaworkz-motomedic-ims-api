<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
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
}