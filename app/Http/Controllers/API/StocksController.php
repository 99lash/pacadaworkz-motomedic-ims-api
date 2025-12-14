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
            $adjustments = $this->stocksService->showStockAdjustments($request->all());
            return StockAdjustmentResource::collection($adjustments)->response();
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
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
            $adjustment = $this->stocksService->showStockAdjustmentsById($id);
            return (new StockAdjustmentResource($adjustment))->response();
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
    public function exportStockAdjustments(Request $request)
    {
        try {
            $filePath = $this->stocksService->exportStockAdjustments($request->all());
            return response()->json(['message' => 'Stock adjustments exported successfully.', 'file_path' => $filePath]);
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
            $movements = $this->stocksService->getStockMovements($request->all());
            return StockMovementResource::collection($movements)->response();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified stock movement.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStockMovementsById($id)
    {
        try {
            $movement = $this->stocksService->showStockMovementsById($id);
            return (new StockMovementResource($movement))->response();
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
            return response()->json(['message' => 'Stock movements exported successfully.', 'file_path' => $filePath]);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred during the export.'], 500);
        }
    }

    /**
     * Display a listing of the stock movements for a specific product.
     *
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStockMovementsbyProductId(Request $request, $productId)
    {
        try {
            $movements = $this->stocksService->getStockMovementsbyProductId($productId, $request->all());
            return StockMovementResource::collection($movements)->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
