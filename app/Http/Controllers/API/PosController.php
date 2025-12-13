<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\Controller;
use App\Http\Requests\POS\Cart\StoreCartItemRequest;
use App\Http\Requests\POS\Cart\UpdateCartItemRequest;
use App\Services\PosService;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;

class PosController extends Controller
{
    private $posService;

    public function __construct(PosService $posService)
    {
        $this->posService = $posService;
    }

    public function show()
    {
        try {

            $userId = Auth::id();
            $result = $this->posService->getCart($userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => CartResource::make($result['cart']),
                    'summary' => $result['summary'],
                ],
                'message' => 'Cart retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                // 'message' => "Internal server error" //least information bai kapag production na
                'message' => $e->getMessage() //comment out kapag dev mode
            ], 500);
        }
    }

    public function store(StoreCartItemRequest $request)
    {
        try {
            $validated = $request->validated();
            $userId = Auth::id();

            $result = $this->posService->addItemToCart($userId, $validated);

            return response()->json([
                'success' => true,
                // 'data' => new CartItemResource($result),
                'data' => CartItemResource::make($result),
                'message' => 'Item added to cart successfully'
            ], 201);
        } catch (\Exception $e) {
            \Log::error('POS Add to Cart Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function update(UpdateCartItemRequest $request, int $id)
    {
        try {
            $validated = $request->validated();
            $userId = Auth::id();

            $result = $this->posService->updateCartItem($userId, $id, $validated['quantity']);

            return response()->json([
                'success' => true,
                'data' => CartItemResource::make($result),
                'message' => 'Cart item updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('POS Update Cart Item Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        return [];
    }

    public function delete(int $id)
    {
        try {
            $userId = Auth::id();

            $this->posService->removeCartItem($userId, $id);

            return response()->json([
                'success' => true,
                'message' => 'Cart item removed successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('POS Delete Cart Item Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'cart_item_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function clearCart()
    {
        try {
            $userId = Auth::id();

            $this->posService->clearCart($userId);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('POS Clear Cart Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function applyDiscount() {

    }
}
