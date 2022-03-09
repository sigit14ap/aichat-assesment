<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\PurchaseTransaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Customer::factory(50)
        ->create()
        ->each(function ($customer) {
            $customer->transactions()->saveMany(
                PurchaseTransaction::factory(rand(1, 5))->withCustomerId($customer->id)->create()
            );
        });

        $this->call([
            VoucherSeeder::class,
        ]);
    }
}
