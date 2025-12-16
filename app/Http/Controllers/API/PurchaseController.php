<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Services\PurchaseService;
use App\Http\Resources\PurchaseOrdersResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Exception;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request)
    {
        try {
             //$search = $request->query('search', null);
            $result = $this->purchaseService->getPurchases();
             
            return response()->json([
                'success' => true,
                 'data' => PurchaseOrdersResource::collection($result)
            ]);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            //
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            //
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Purchase order not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //
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