<?php

namespace App\Services\Transaction;

use App\Services\Transaction\Interfaces\TransactionServiceInterface;
use App\Models\PurchaseTransaction;
use DB;

class TransactionService implements TransactionServiceInterface
{
    /**
     * Count total transaction within the last 30 days
     * @param int $customer_id
     * @return bool
     */
    public function totalTransaction(int $customer_id) : bool
    {
        $currentDate = date('Y-m-d H:i:s');
        $beforeDate = date('Y-m-d H:i:s', strtotime($currentDate. ' - 30 days'));

        $total = PurchaseTransaction::where('customer_id', '=', $customer_id)
        ->whereBetween('transaction_at', [$beforeDate, $currentDate])
        ->count();
        
        return $total > 2;
    }

     /**
     * Sum total nominal transaction equal or more than $100
     * @param int $customer_id
     * @return bool
     */
    public function totalNominal(int $customer_id) : bool
    {
        $total = PurchaseTransaction::select(DB::raw('SUM(total_spent) as total_nominal'), 'customer_id')
        ->where('customer_id', '=', $customer_id)
        ->groupBy('customer_id')
        ->first();
        
        return $total->total_nominal >= 100;
    }
}
