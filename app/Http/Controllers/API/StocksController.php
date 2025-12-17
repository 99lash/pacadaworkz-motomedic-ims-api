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

    /**
     * Display a listing of the stock adjustments.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStockAdjustments(Request $request)
    {
        try {
            $result = $this->stocksService->showStockAdjustments($request->all());
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

    /**
     * Display the specified stock adjustment.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Export stock adjustments to a file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportStockAdjustments()
    {
        try {
            $filePath = $this->stocksService->exportStockAdjustments();
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred during the export.'], 500);
        }
    }

    /**
     * Display a listing of the stock movements.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStockMovements(Request $request)
    {
        try {
        $result = $this->stocksService->getStockMovements($request->all());
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

    /**
     * Display the specified stock movement.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStockMovementsById(int $id)
    {
        try {
            $result = $this->stocksService->showStockMovementsById($id);
            //return (new StockMovementResource($movement))->response();

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

    /**
     * Export stock movements to a file.
     *
        * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
