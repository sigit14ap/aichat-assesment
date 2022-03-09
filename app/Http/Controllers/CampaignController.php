<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Customer\Interfaces\CustomerServiceInterface;
use App\Services\Transaction\Interfaces\TransactionServiceInterface;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CampaignController extends Controller
{
    private $user;
    private $customer;
    private $customerService;
    private $campaign_end_at = '2022-03-19 10:00:00';

    public function __construct(Request $request, CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;

        $this->middleware(function ($request, $next) {
            
            if(date('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime($this->campaign_end_at))){
                return Response::badRequest('Campaign are locked down');
            }else{
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
     * @return  Illuminate\Http\JsonResponse
     */
    public function eligible_check(TransactionServiceInterface $transactionService) : JsonResponse
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

        return Response::successWithDataResponse('Success', []);
    }
}
