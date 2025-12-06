<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
use App\Services\InventoryService;
use App\Http\Resources\InventoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\InventoryRequest; // Import the InventoryRequest

class InventoryController extends Controller // Extend Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->query('search', null);
            $result = $this->inventoryService->getAllInventory($search);

            return response()->json([
                'success' => true,
                'data' => InventoryResource::collection($result),
                'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage(),
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(InventoryRequest $request)
    {
        try {
            $result = $this->inventoryService->createInventory($request->validated());

            return response()->json([
                'success' => true,
                'data' => new InventoryResource($result),
            ], 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $result = $this->inventoryService->getInventoryById($id);

            return response()->json([
                'success' => true,
                'data' => new InventoryResource($result),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(InventoryRequest $request, $id)
    {
        try {
            $result = $this->inventoryService->updateInventory($request->validated(), $id);

            return response()->json([
                'success' => true,
                'data' => new InventoryResource($result),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->inventoryService->deleteInventory($id);

            return response()->json([
                'success' => true,
                'message' => 'Inventory item deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

