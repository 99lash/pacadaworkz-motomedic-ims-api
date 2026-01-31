<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\SalesItem;
use App\Models\Inventory;
use App\Exceptions\POS\Cart\CartItemNotFoundException;
use App\Exceptions\POS\Cart\EmptyCartException;
use App\Exceptions\Inventory\InsufficientStockException;
use App\Exceptions\POS\InsufficientPaymentException;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosService
{
    public function __construct(private ActivityLogService $activityLogService)
    {
    }

    public function getCart(int $userId)
    {
        //create cart kung waley pa
        $cart = $this->createCart($userId);

        //woah load lopet orm
        $cart->load('cart_items.product');

        $result['cart'] = $cart;

        $itemsCount = $cart->cart_items->count();
        $totalQuantity = $cart->cart_items->sum('quantity');
        $subtotal = $cart->cart_items->sum(fn($item) => $item->unit_price * $item->quantity);
        $discountAmount = 0.00;

        if ($cart->discount > 0) {
            if ($cart->discount_type === 'percentage') {
                $discountAmount = $subtotal * ($cart->discount / 100);
            } else { // fixed
                $discountAmount = $cart->discount;
            }
        }
        $total = $subtotal - $discountAmount;
        if ($total < 0) {
            $total = 0;
        }

        $result['summary'] = [
            'items_count' => $itemsCount,
            'total_quantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
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
            [
                'product_id' => $productId,
                'quantity' => 1,
                'unit_price' => intval($product->unit_price),
            ]
        );

        if (!$cart_item->wasRecentlyCreated) {
            $cart_item->quantity += 1;
            $cart_item->save();
        }

              //find name first of the product
              $name=$product->name;
              
          
            $this->activityLogService->log(
                module: 'POS',
                action: 'Create',
                description: "Add item to cart for product {$name}, quantity: 1",
                userId: $userId
            );
        return $cart_item;
    }

    public function updateCartItem(int $userId, int $cartItemId, int $quantity)
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();

        $cartItem = $cart->cart_items()->where('id', $cartItemId)->first();

        if (!$cartItem)
            throw new CartItemNotFoundException();

        $cartItem->quantity = $quantity;
        $cartItem->save();

        $cartItem->load('product');

        return $cartItem;
    }

    public function removeCartItem(int $userId, int $cartItemId)
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();

        $cartItem = $cart->cart_items()->where('id', $cartItemId)->first();

        if (!$cartItem)
            throw new CartItemNotFoundException();

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
        $cart = $this->createCart($userId);

        if (!$cart->cart_items()->exists())
            throw new EmptyCartException();

        $cart->discount = $discountDetails['discount'];
        $cart->discount_type = $discountDetails['discount_type'];
        $cart->save();

        return $cart;
    }

    public function processCheckout(int $userId, array $paymentDetails)
    {
        return DB::transaction(function () use ($userId, $paymentDetails) {
            $cart = Cart::where('user_id', $userId)->with('cart_items')->firstOrFail();

            if ($cart->cart_items->isEmpty())
                throw new EmptyCartException();

            //calculate totals and validate stock
            $subtotal = 0;
            $cartItems = $cart->cart_items;

            foreach ($cartItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();

                if (!$inventory || $inventory->quantity < $item->quantity)
                    throw new InsufficientStockException("Insufficient stock for product ID: " . $item->product_id);

                $subtotal += $item->quantity * $item->unit_price;
            }

            $discountAmount = 0;
            if ($cart->discount > 0) {
                if ($cart->discount_type === 'percentage') {
                    $discountAmount = $subtotal * ($cart->discount / 100);
                } else {
                    $discountAmount = $cart->discount;
                }
            }

            $totalAmount = max(0, $subtotal - $discountAmount);

            // Calculate change
            $amountTendered = isset($paymentDetails['amount_tendered']) ? floatval($paymentDetails['amount_tendered']) : $totalAmount;

            if ($amountTendered < $totalAmount)
                throw new InsufficientPaymentException("Payment is less than the total amount of {$totalAmount}");

            $change = max(0, $amountTendered - $totalAmount);

            //create sales transaction
            $transaction = SalesTransaction::create([
                'user_id' => $userId,
                'transaction_no' => 'TRX-' . date('Ymd') . '-' . Str::upper(Str::random(6)),
                'subtotal' => $subtotal,
                'tax' => 0,
                'discount' => $discountAmount,
                'discount_type' => $cart->discount_type,
                'total_amount' => $totalAmount,
                'payment_method' => Str::lower($paymentDetails['payment_method']),
                'amount_tendered' => $amountTendered,
                'change' => $change,
            ]);

            //create sales items along with its stock movement and deduct inventory stock
            foreach ($cartItems as $item) {
                SalesItem::create([
                    'sales_transactions_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ]);

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => $userId,
                    'movement_type' => 'out',
                    'quantity' => $item->quantity,
                    'reference_type' => 'sale',
                    'reference_id' => $transaction->id,
                    'notes' => "POS Checkout - Transaction # {$transaction->transaction_no}",
                ]);

                //deduct inventory
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                $inventory->decrement('quantity', $item->quantity);
            }

            // Clear Cart
            $cart->cart_items()->delete();
            $cart->update(['discount' => 0, 'discount_type' => 'fixed']);

            $this->activityLogService->log(
                module: 'POS',
                action: 'Create',
                description: "Completed sales transaction #{$transaction->transaction_no} with a total of {$transaction->total_amount}",
                userId: $userId
            );

            return $transaction->load('sales_items');
        });
    }

    private function createCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId], ['user_id' => $userId]);
    }
}
