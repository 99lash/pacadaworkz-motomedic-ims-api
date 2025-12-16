<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
use App\Services\SalesService;
use App\Http\Resources\SalesTransactionResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SalesController extends Controller
{
    protected $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            // Extract specific filters
            $filters = $request->only(['user_id', 'payment_method', 'start_date', 'end_date']);
            
            // Call service
            $result = $this->salesService->getAllSales($search, $filters);

            return response()->json([
                'success' => true,
                'data' => SalesTransactionResource::collection($result),
                'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Sales Get All Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                // 'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $result = $this->salesService->getSalesById($id);
            return response()->json([
                'success' => true,
                'data' => new SalesTransactionResource($result)
            ]);
        } catch (ModelNotFoundException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Sales transaction not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}