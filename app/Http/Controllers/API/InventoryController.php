<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InventoryService;
use App\Http\Resources\InventoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class InventoryController
{


    protected $inventoryService;

    public function __construct(InventoryService $inventoryService){
      
        $this->inventoryService = $inventoryService;
           
    }

    public function index(Request $request){
    
          try {
          
              $search = $request->query('search',null);
            $result = $this->inventoryService->getAllInventory($search);

                return response()->json([
             'success' => true,
             'data' => InventoryResource::collection($result),
              'meta' => [
              'current_page' => $result->currentPage(),
              'per_page' => $result->perPage(),
              'total' => $result->total(),
              'last_page' => $result->lastPage()
        ]
     ]);

          }catch(ModelNotFoundException $e){
             return response()->json([
                'success' => false,
                'message' => 'Inventory not found'
            ], 404);
          }catch(\Exception $e){
                return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
          }

      
           
         
    }

    public function show($id)
    {
        try {
            // Logic to show a specific inventory item
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Logic to update a specific inventory item
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Logic to delete a specific inventory item
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



}

