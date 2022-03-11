<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Customer\Interfaces\CustomerServiceInterface;
use App\Services\Transaction\Interfaces\TransactionServiceInterface;
use App\Services\Voucher\Interfaces\VoucherServiceInterface;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use App\Http\Requests\SubmissionRequest;

class CampaignController extends Controller
{
    private $user;
    private $customer;
    private $voucher;
    private $voucherService;
    private $customerService;
    private $campaign_end_at = '2022-04-01 10:00:00';

    public function __construct(Request $request, CustomerServiceInterface $customerService, VoucherServiceInterface $voucherService)
    {
        $this->voucherService   =   $voucherService;
        $this->customerService  =   $customerService;

        $this->middleware(function ($request, $next) {
            
            if(date('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime($this->campaign_end_at))){
                return Response::badRequest('Campaign are locked down');
            }else{

                /**
                 * Set expired is required if we not cron job
                 * cron job is better for this
                 * But for the example we don't use cron job and set expired manually
                 */
                $set_expired = $this->voucherService->setExpired();

                if($request->has('email')){
                
                    $customer = $this->customerService->findByEmail($request->email);
        
                    if($customer){
                        $this->customer = $customer;

                        return $next($request);
                    }else{
                        return Response::badRequest('Email not registered as customer');
                    }
                }else{
                    return Response::validationResponse('Unprocessable Content', [
                        'email' =>  'The email field is required.'
                    ]);
                }
            }
        });
    }

    /**
     * Check if user is eligible for redeem voucher
     * @method  POST
     * @link    '/api/v1/eligible-check'
     * @param   TransactionServiceInterface $service
     * @param   VoucherServiceInterface $voucherService
     * @return  Illuminate\Http\JsonResponse
     */
    public function eligible_check(TransactionServiceInterface $transactionService, VoucherServiceInterface $voucherService) : JsonResponse
    {
        $customer = $this->customer;

        $total_transaction = $transactionService->totalTransaction($customer->id);

        if(!$total_transaction){
            return Response::withCode(403, 'Total transactions in the last 30 days minimum 3 transactions');
        }

        $total_nominal = $transactionService->totalNominal($customer->id);

        if(!$total_nominal){
            return Response::withCode(403, 'Minimum total nominal is equal or more than $100');
        }

        $check_redeem = $voucherService->isAlreadyReedem($customer->id);

        if($check_redeem){
            return Response::withCode(403, 'You are already redeem voucher or registered to a voucher');
        }

        $latest_voucher = $voucherService->latestVoucher($customer->id);

        if(!is_null($latest_voucher)){
            return Response::withCode(403, 'Your chance to claim voucher only 1 times');
        }
        
        $available_voucher = $this->voucherService->availableVoucher();

        if(!$available_voucher){
            return Response::badRequest('All voucher fully redeemed');
        }

        $register = $voucherService->registerToVoucher($available_voucher, $customer->id);

        return Response::successWithDataResponse('Customer are eligible and registered to a voucher', $register);
    }

    /**
     * Submit photo submission for get voucher
     * @method  POST
     * @link    '/api/v1/submission'
     * @param   SubmissionRequest $request
     * @param   VoucherServiceInterface $voucherService
     * @return  Illuminate\Http\JsonResponse
     */
    public function submission(SubmissionRequest $request, VoucherServiceInterface $voucherService) : JsonResponse
    {
        $customer = $this->customer;

        $voucherCustomer = $voucherService->latestVoucher($customer->id);

        if(is_null($voucherCustomer)){
            return Response::withCode(403, 'Please check eligible first');
        }
        elseif($voucherCustomer->status == 'failed'){
            return Response::withCode(403, 'Submission expired');
        }
        elseif(!$voucherService->imageRecognition()){
            return Response::withCode(400, 'Image recognition failed');
        }
        elseif($voucherCustomer->voucher->redeem_at){
            return Response::withCode(403, 'Voucher already redeemed');
        }
        else{
            $redeem = $voucherService->redeemVoucher($voucherCustomer);

            return Response::successWithDataResponse('Success redeem voucher', $redeem);
        }
    }
}
