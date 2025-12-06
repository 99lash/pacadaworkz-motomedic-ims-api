<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->query('search', null);
            $suppliers = $this->supplierService->getAllSuppliers($search);
            return SupplierResource::collection($suppliers);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(SupplierRequest $request)
    {
        try {
            $supplier = $this->supplierService->createSupplier($request->validated());
            return new SupplierResource($supplier);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $supplier = $this->supplierService->getSupplierById($id);
            return new SupplierResource($supplier);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Supplier not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(SupplierRequest $request, $id)
    {
        try {
            $supplier = $this->supplierService->updateSupplier($request->validated(), $id);
            return new SupplierResource($supplier);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Supplier not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->supplierService->deleteSupplier($id);
            return response()->json(['message' => 'Supplier deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Supplier not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
