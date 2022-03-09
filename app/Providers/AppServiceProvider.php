<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Customer\Interfaces\CustomerServiceInterface;
use App\Services\Customer\CustomerService;
use App\Services\Transaction\Interfaces\TransactionServiceInterface;
use App\Services\Transaction\TransactionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
    }
}
