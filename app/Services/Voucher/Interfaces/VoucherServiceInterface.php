<?php

namespace App\Services\Voucher\Interfaces;

use App\Models\Voucher;
use App\Models\VoucherCustomer;

interface VoucherServiceInterface
{
    /**
     * Check is user already redeem voucher
     * @param   int $customer_id
     * @return  bool
     */
    public function isAlreadyReedem(int $customer_id) : bool;

    /**
     * Check if there is an unused voucher
     * @return  App\Models\Voucher || NULL
     */
    public function availableVoucher() : ?Voucher;

    /**
     * Register customer to a voucher
     * @param   App\Models\Voucher $voucher
     * @param   int $customer_id
     * @return  App\Models\VoucherCustomer || NULL
     */
    public function registerToVoucher(Voucher $voucher, int $customer_id) : ?VoucherCustomer;

    /**
     * Set Expired all voucher customer
     * @return  void
     */
    public function setExpired() : void;

    /**
     * Find latest voucher for customer
     * @param   int $customer_id
     * @return  App\Models\VoucherCustomer || NULL
     */
    public function latestVoucher(int $customer_id) : ?VoucherCustomer;

    /**
     * Image recognition submission
     * @return  bool
     */
    public function imageRecognition() : bool;

    /**
     * Redeem voucher customer
     * @param   App\Models\VoucherCustomer $voucherCustomer
     * @return  App\Models\Voucher
     */
    public function redeemVoucher(VoucherCustomer $voucherCustomer) : Voucher;
}