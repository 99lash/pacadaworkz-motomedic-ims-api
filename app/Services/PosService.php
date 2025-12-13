<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;

class PosService
{
    public function getCart(int $userId)
    {
        //create cart kung waley pa
        $cart = $this->createCart($userId);

        //woah load lopet orm
        $cart->load('cart_items.product');

        $result['cart'] = $cart;

        $itemsCount = $cart->cart_items->count();
        $totalQuantity = $cart->cart_items->sum('quantity');
        $subtotal = $cart->cart_items->sum(fn($item) => $item->product->unit_price * $item->quantity);
        $discount = 0.00;
        $total = $subtotal - $discount;

        $result['summary'] = [
            'items_count' => $itemsCount,
            'total_quantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ];
        return $result;
    }

    public function addItemToCart(int $userId, array $itemDetails)
    {
        $productId = $itemDetails['product_id'];

        //validate Product exists and is not soft-deleted
        $product = Product::findOrFail($productId);

        $cart = $this->createCart($userId);

        // $quantity = intval($itemDetails['quantity']);

        $cart_item = $cart->cart_items()->firstOrCreate(
            ['product_id' => $productId],
            ['product_id' => $productId, 'quantity' => 1]
        );

        if (!$cart_item->wasRecentlyCreated) {
            $cart_item->quantity += 1;
            $cart_item->save();
        }
        return $cart_item;
    }

    public function updateCartItem(int $userId, int $cartItemId, int $quantity)
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();

        $cartItem = $cart->cart_items()->where('id', $cartItemId)->firstOrFail();

        $cartItem->quantity = $quantity;
        $cartItem->save();

        $cartItem->load('product');

        return $cartItem;
    }

    public function removeCartItem(int $userId, int $cartItemId)
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();

        $cartItem = $cart->cart_items()->where('id', $cartItemId)->firstOrFail();

        $cartItem->delete();

        return true;
    }

    public function clearCart(int $userId)
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();

        $cart->cart_items()->delete();

        return true;
    }

    public function applyDiscount(int $userId, array $discountDetails)
    {
        // TODO: Implement logic to apply discount to the cart
        return [];
    }

    public function processCheckout(int $userId, array $paymentDetails): array
    {
        // TODO: Implement checkout logic (e.g., validate payment, create sales transaction, deduct stock)
        return [];
    }

    private function createCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId], ['user_id' => $userId]);
    }
}
