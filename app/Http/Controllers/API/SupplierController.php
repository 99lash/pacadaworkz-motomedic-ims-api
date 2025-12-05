<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;
use Exception;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $suppliers = $this->supplierService->getAllSuppliers(); // Calls to a non-existent service method
            return SupplierResource::collection($suppliers);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve suppliers', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SupplierRequest  $request
     * @return \App\Http\Resources\SupplierResource|\Illuminate\Http\JsonResponse
     */
    public function store(SupplierRequest $request)
    {
        try {
            $supplier = $this->supplierService->createSupplier($request->validated()); // Calls to a non-existent service method
            return new SupplierResource($supplier);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create supplier', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \App\Http\Resources\SupplierResource|\Illuminate\Http\JsonResponse
     */
    public function show(Supplier $supplier)
    {
        try {
            $supplier = $this->supplierService->getSupplierById($supplier); // Calls to a non-existent service method
            return new SupplierResource($supplier);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve supplier', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SupplierRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \App\Http\Resources\SupplierResource|\Illuminate\Http\JsonResponse
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            $updatedSupplier = $this->supplierService->updateSupplier($supplier, $request->validated()); // Calls to a non-existent service method
            return new SupplierResource($updatedSupplier);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update supplier', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierService->deleteSupplier($supplier); // Calls to a non-existent service method
            return response()->json(['message' => 'Supplier deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete supplier', 'error' => $e->getMessage()], 500);
        }
    }
}
