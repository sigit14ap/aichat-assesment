<?php

namespace App\Services\Customer;

use App\Services\Customer\Interfaces\CustomerServiceInterface;
use App\Models\Customer;

class CustomerService implements CustomerServiceInterface
{
    /**
     * Find customer by email
     * @param string $email
     * @return App\Models\Customer || null
     */
    public function findByEmail(string $email) : ?Customer
    {
        return Customer::where('email', '=', $email)->first();
    }
}
