<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseTransaction>
 */
class PurchaseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = strtotime('2022-01-01 10:00:00');
        $end = time();
        $timestamp = mt_rand($start, $end);

        return [
            'total_spent'       =>  rand(1, 100),
            'total_saving'      =>  rand(1, 100),
            'transaction_at'    =>  date('Y-m-d H:i:s', $timestamp),
        ];
    }

    public function withCustomerId($id)
    {
        return $this->state([
            'customer_id' => $id
        ]);
    }
}
