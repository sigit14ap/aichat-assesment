<?php

namespace App\Services\Customer\Interfaces;

use App\Models\Customer;

interface CustomerServiceInterface
{
    /**
     * Find customer by email
     * @param string $email
     * @return App\Models\Customer || NULL
     */
    public function findByEmail(string $email) : ?Customer;
}