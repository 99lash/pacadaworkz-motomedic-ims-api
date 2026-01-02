<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Http\Resources\StockAdjustmentResource;
use App\Http\Resources\StockMovementResource;
use App\Services\StocksService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    protected $stocksService;

    public function __construct(StocksService $stocksService)
    {
        $this->stocksService = $stocksService;
    }

     // show stocks adjustments
    public function showStockAdjustments(Request $request)
    {
        try {
             $search = $request->query('search', null);
            $result = $this->stocksService->showStockAdjustments($search);
            //return StockAdjustmentResource::collection($adjustments)->response();
            return response()->json([
                'success' => true,
                 'data' => StockAdjustmentResource::collection($result),
                  'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage(),
                ],
                 
            ]);
        } catch (Exception $e) {
            // return response()->json(['message' => 'An unexpected error occurred.'], 500);
             return response()->json(['message' => $e->getMessage()], 500);
        }
    }

   // Display the specified stock adjustment.

    public function showStockAdjustmentsById($id)
    {
        try {
            $result = $this->stocksService->showStockAdjustmentsById($id);
            //return (new StockAdjustmentResource($adjustment))->response();
          return response()->json([
                'success' => true,
                 'data' => new StockAdjustmentResource($result)
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Stock adjustment not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    // Export stock adjustments to a file.
 
    public function exportStockAdjustments()
    {
        try {
            $filePath = $this->stocksService->exportStockAdjustments();
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred during the export.'], 500);
        }
    }

    // Display a listing of the stock movements.
    
    public function showStockMovements(Request $request)
    {
        try {
            $search = $request->query('search', null);
        $result = $this->stocksService->getStockMovements($search);
            //  return StockMovementResource::collection($movements)->response();
            return response()->json(
                [
                    'success' => true,
                    'data' => StockMovementResource::collection($result),
                     'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage(),
                ],
                ]
            );
        } catch (Exception $e) {
            return response()->json(['message' =>  'An error occured',], 500);
        }
    }

  // Display the specified stock movement.
   
    public function showStockMovementsById(int $id)
    {
        try {
            $result = $this->stocksService->showStockMovementsById($id);
           

            return response()->json([
                'success' => true,
                'data' => new StockMovementResource($result)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Stock movement not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
// Export stock movements to a file.

    public function exportStockMovements(Request $request)
    {
        try {
            $filePath = $this->stocksService->exportStockMovements($request->all());
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred during the export.'], 500);
        }
    }

 
}
