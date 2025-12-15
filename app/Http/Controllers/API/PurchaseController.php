<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

    public function index()
    {
        try {
            //
        } catch (Exception $e) {
            //
        }
    }

    public function store(Request $request)
    {
        try {
            //
        } catch (Exception $e) {
            //
        }
    }

    public function show($id)
    {
        try {
            //
        } catch (ModelNotFoundException $e) {
            //
        } catch (Exception $e) {
            //
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //
        } catch (ModelNotFoundException $e) {
            //
        } catch (Exception $e) {
            //
        }
    }

    public function destroy($id)
    {
        try {
            //
        } catch (ModelNotFoundException $e) {
            //
        } catch (Exception $e) {
            //
        }
    }
}