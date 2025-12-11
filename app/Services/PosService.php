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
            'discount' =>$discount,
            'total' => $total,
        ];

        return $result;
    }

    private function createCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId], ['user_id' => $userId]);
    }
}
