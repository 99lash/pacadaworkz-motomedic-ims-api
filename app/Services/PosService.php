<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;

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
        $cart = $this->createCart($userId);

        $productId = $itemDetails['product_id'];
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
        // TODO: Implement logic to update cart item quantity
        return [];
    }

    public function removeCartItem(int $userId, int $cartItemId)
    {
        // TODO: Implement logic to remove item from cart
        return [];
    }

    public function clearCart(int $userId)
    {
        // TODO: Implement logic to clear all items from user's cart
        return [];
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
