<?php

namespace App\Services;

use App\Exceptions\Sales\SalesTransactionNotFoundException;
use App\Exceptions\Sales\InvalidRefundSalesTransactionException;
use App\Models\Inventory;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\DB;

use App\Models\StockMovement;

class SalesService
{

    public function getAllSales($search = null, $filters = [])
    {
        $query = SalesTransaction::with(['user', 'sales_items.product']);

        if ($search) {
            $query->where('transaction_no', 'LIKE', "%{$search}%");
        }

        if (!empty($filters)) {
            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
            if (isset($filters['payment_method'])) {
                $query->where('payment_method', $filters['payment_method']);
            }
            if (isset($filters['start_date'])) {
                $query->whereDate('created_at', '>=', $filters['start_date']);
            }
            if (isset($filters['end_date'])) {
                $query->whereDate('created_at', '<=', $filters['end_date']);
            }
        }

        // Default sort
        $query->orderBy('created_at', 'desc');

        return $query->paginate(10)->withQueryString();
    }

    public function getSalesById($id)
    {
        $salesTransaction = SalesTransaction::with(['user', 'sales_items.product'])->find($id);

        if (!$salesTransaction)
            throw new SalesTransactionNotFoundException();

        return $salesTransaction;
    }

    public function voidTransaction($userId, $salesId)
    {
        return DB::transaction(function () use ($userId, $salesId) {
            $salesTransaction = SalesTransaction::with('sales_items')
                ->where([
                    'id' => $salesId,
                    'user_id' => $userId
                ])->first();

            if (!$salesTransaction) {
                throw new SalesTransactionNotFoundException();
            }

            if ($salesTransaction->status === 'voided') {
                return $salesTransaction;
            }

            // Restore stock
            foreach ($salesTransaction->sales_items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }

            $salesTransaction->status = 'voided';
            $salesTransaction->save();

            return $salesTransaction;
        });
    }

    public function refundTransaction($userId, $salesId, $data)
    {
        return DB::transaction(function () use ($userId, $salesId, $data) {
            $salesTransaction = SalesTransaction::with('sales_items')
                ->where('id', $salesId)
                ->first();

            if (!$salesTransaction) {
                throw new SalesTransactionNotFoundException();
            }

            if ($salesTransaction->status === 'voided') {
                throw new InvalidRefundSalesTransactionException('Cannot refund a voided transaction.');
            }

            if ($salesTransaction->status === 'refunded') {
                throw new InvalidRefundSalesTransactionException('The sales transaction is already fully refunded.');
            }

            $refundAmount = 0;
            $refundType = $data['refund_type'] ?? 'partial';
            $mainReason = $data['reason'] ?? null;

            if ($refundType === 'full') {
                foreach ($salesTransaction->sales_items as $item) {
                    $remainingQty = $item->quantity - $item->quantity_returned;
                    if ($remainingQty > 0) {
                        // Restore Inventory
                        $inventory = Inventory::where('product_id', $item->product_id)->first();
                        if ($inventory) {
                            $inventory->increment('quantity', $remainingQty);
                        }

                        // Create Stock Movement
                        StockMovement::create([
                            'product_id' => $item->product_id,
                            'user_id' => $userId,
                            'movement_type' => 'in',
                            'quantity' => $remainingQty,
                            'reference_type' => 'return',
                            'reference_id' => $salesTransaction->id,
                            'notes' => 'Full Refund: ' . $mainReason
                        ]);

                        $item->quantity_returned = $item->quantity;
                        $item->save();
                    }
                }
                $refundAmount = $salesTransaction->total_amount - $salesTransaction->refund_amount;
                $salesTransaction->status = 'refunded';

            } else {
                // Partial
                foreach ($data['refund_items'] as $refundItem) {
                    $item = $salesTransaction->sales_items->where('id', $refundItem['sales_item_id'])->first();

                    if (!$item) {
                        throw new InvalidRefundSalesTransactionException("Invalid sales item ID: {$refundItem['sales_item_id']} does not belong to this transaction.");
                    }

                    $qtyToRefund = $refundItem['quantity'];
                    $currentReturned = $item->quantity_returned;

                    if ($qtyToRefund + $currentReturned > $item->quantity) {
                        throw new InvalidRefundSalesTransactionException("Cannot refund more than sold quantity for item {$item->product_id}");
                    }

                    // Restore Inventory
                    $inventory = Inventory::where('product_id', $item->product_id)->first();
                    if ($inventory) {
                        $inventory->increment('quantity', $qtyToRefund);
                    }

                    // Stock Movement
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'user_id' => $userId,
                        'movement_type' => 'in',
                        'quantity' => $qtyToRefund,
                        'reference_type' => 'return',
                        'reference_id' => $salesTransaction->id,
                        'notes' => 'Partial Refund: ' . ($refundItem['reason'] ?? $mainReason)
                    ]);

                    $item->quantity_returned += $qtyToRefund;
                    $item->save();

                    $refundAmount += $item->unit_price * $qtyToRefund;
                }
            }

            $salesTransaction->refund_amount += $refundAmount;
            $salesTransaction->refund_reason = $mainReason;
            $salesTransaction->refunded_at = now();

            if ($salesTransaction->refund_amount >= $salesTransaction->total_amount) {
                $salesTransaction->status = 'refunded';
            } else {
                $salesTransaction->status = 'partially_refunded';
            }

            $salesTransaction->save();

            return $salesTransaction;
        });
    }
}
