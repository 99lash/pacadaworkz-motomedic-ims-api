<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

      // get all Suppliers
     function index()
    {
        try {
            $result = $this->supplierService->getAllSuppliers(); // Calls to a non-existent service method
            return response()->json([
             'success' => true,
             'data' => SupplierResource::collection($result),
             'meta' => [
            'current_page' => $result->currentPage(),
            'per_page' => $result->perPage(),
            'total' => $result->total(),
            'last_page' => $result->lastPage()
        ]
            ]); 
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve suppliers', 'error' => $e->getMessage()], 500);
        }
    }

    //create new Suppliers
    public function store(SupplierRequest $request)
    {
        try {
            $supplier = $this->supplierService->createSupplier($request->validated()); // Calls to a non-existent service method
            return response()->json([
                'success' => true,
                'data' => new SupplierResource($supplier)
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create supplier', 'error' => $e->getMessage()], 500);
        }
    }


    //show specific supplier
    public function show($id)
    {
        try {
            $supplier = $this->supplierService->getSupplierById($id); // Calls to a non-existent service method
            return response()->json([
                'success' => true,
                'data' => new SupplierResource($supplier)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Supplier not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve supplier', 'error' => $e->getMessage()], 500);
        }
    }

  
    //update supplier
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            $updatedSupplier = $this->supplierService->updateSupplier($supplier, $request->validated()); // Calls to a non-existent service method
            return response()->json([
                'success' => true,
                'data' => new SupplierResource($updatedSupplier)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Supplier not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update supplier', 'error' => $e->getMessage()], 500);
        }
    }

 
    //delete supplier
    public function destroy($id)
    {
        try {
            $this->supplierService->deleteSupplier($id); // Calls to a non-existent service method
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Supplier not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete supplier', 'error' => $e->getMessage()], 500);
        }
    }
}
