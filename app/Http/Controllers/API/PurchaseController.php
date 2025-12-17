<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Services\PurchaseService;
use App\Http\Resources\PurchaseOrdersResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductAttributeRequest;
use App\Http\Requests\Purchase\PurchaseOrdersRequest;
use Exception;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
 

    // show all purchase
    public function index(Request $request)
    {
        try {
             $search = $request->query('search', null);
            $result = $this->purchaseService->getPurchases($search);
             
            return response()->json([
                'success' => true,
                 'data' => PurchaseOrdersResource::collection($result)
            ]);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    //store new Purchase
    public function store(PurchaseOrdersRequest $request)
    {
        try {
            $result = $this->purchaseService->createPurchase($request->validated());
            return response()->json([
                'success' => true,
                'data' => new PurchaseOrdersResource($result)
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
 // get by id 
    public function show($id)
    {
        try {
            
            $result = $this->purchaseService->findPurchase($id);
             
            return [
                'success' => true,
                'data' => new PurchaseOrdersResource($result)
            ];

        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Purchase order not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
   //update purchase order
    public function update(PurchaseOrdersRequest $request, $id)
    {
        try {
            $result = $this->purchaseService->updatePurchase($id, $request->validated());
            return response()->json([
                'success' => true,
                'data' => new PurchaseOrdersResource($result)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Purchase order not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            //
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Purchase order not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}