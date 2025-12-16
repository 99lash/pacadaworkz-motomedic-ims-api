<?php

namespace App\Services;

use App\Exceptions\Sales\SalesTransactionNotFoundException;
use App\Models\SalesTransaction;

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
}
