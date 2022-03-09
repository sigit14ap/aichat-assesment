<?php

namespace App\Services\Transaction\Interfaces;

use App\Models\PurchaseTransaction;

interface TransactionServiceInterface
{
    /**
     * Count total transaction within the last 30 days
     * @param int $customer_id
     * @return bool
     */
    public function totalTransaction(int $customer_id) : bool;

    /**
     * Sum total nominal transaction equal or more than $100
     * @param int $customer_id
     * @return bool
     */
    public function totalNominal(int $customer_id) : bool;
}