<?php

namespace App\Services\Voucher;

use App\Services\Voucher\Interfaces\VoucherServiceInterface;
use App\Models\Voucher;
use App\Models\VoucherCustomer;

class VoucherService implements VoucherServiceInterface
{
    /**
     * Check is user already redeem voucher
     * @param   int $customer_id
     * @return  bool
     */
    public function isAlreadyReedem(int $customer_id) : bool
    {
        $check = VoucherCustomer::where('customer_id', '=', $customer_id)->whereIn('status', ['waiting', 'success'])->first();

        return !is_null($check);
    }

    /**
     * Check if there is an unused voucher
     * @return  App\Models\Voucher || NULL
     */
    public function availableVoucher() : ?Voucher
    {
        return Voucher::whereNull('redeem_at')
        ->where(function($query){
            $query->doesntHave('customer')
            ->orWhereHas('customer', function($q){
                $q->whereNotIn('status', ['waiting', 'success']);
            });
        })
        ->first();
    }

    /**
     * Register customer to a voucher
     * @param   App\Models\Voucher $voucher
     * @param   int $customer_id
     * @return  App\Models\VoucherCustomer || NULL
     */
    public function registerToVoucher(Voucher $voucher, int $customer_id) : ?VoucherCustomer 
    {
        $now = date('Y-m-d H:i:s');
        $expired_at = date('Y-m-d H:i:s', strtotime($now. ' + 10 minutes'));

        return VoucherCustomer::create([
            'customer_id'   =>  $customer_id,
            'voucher_id'    =>  $voucher->id,
            'expired_at'    =>  $expired_at,
            'status'        =>  'waiting'
        ]);
    }

    /**
     * Set Expired all voucher customer
     * @return  void
     */
    public function setExpired() : void
    {
        VoucherCustomer::where('expired_at', '<=', date('Y-m-d H:i:s'))->where('status', '=', 'waiting')->update(['status' => 'failed']);
    }

    /**
     * Find latest voucher for customer
     * @param   int $customer_id
     * @return  App\Models\VoucherCustomer || NULL
     */
    public function latestVoucher(int $customer_id) : ?VoucherCustomer
    {
        return VoucherCustomer::where('customer_id', '=', $customer_id)->latest()->first();
    }

    /**
     * Image recognition submission with random boolean
     * @return  bool
     */
    public function imageRecognition() : bool
    {
        return (bool)random_int(0, 1);
    }

    /**
     * Redeem voucher customer
     * @param   App\Models\VoucherCustomer $voucherCustomer
     * @return  App\Models\Voucher
     */
    public function redeemVoucher(VoucherCustomer $voucherCustomer) : Voucher
    {
        $voucherCustomer->update(['status' => 'success']);
        $voucherCustomer->voucher->update(['redeem_at' => date('Y-m-d H:i:s')]);
        
        return Voucher::find($voucherCustomer->voucher_id);
    }
}
