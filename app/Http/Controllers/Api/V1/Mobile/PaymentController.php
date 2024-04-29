<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Resources\PaymentResource;
use App\Repositories\Contracts\PaymentContract;

class PaymentController extends BaseApiController
{
    /**
     * PaymentController constructor.
     * @param PaymentContract $paymentContract
     */
    private PaymentContract $paymentContract;

    /**
     * PaymentController constructor.
     * @param PaymentContract $paymentContract
     */
    public function __construct(PaymentContract $paymentContract)
    {
        parent::__construct($paymentContract, PaymentResource::class);
        $this->relations = ['payer', 'beneficiary', 'currency', 'payable'];
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function doctorIndex()
    {
        $this->defaultScopes = ['doctor' => auth()->user()->doctor?->id];
        return parent::index();
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function patientIndex()
    {
        $user = auth()->user();
        $this->defaultScopes = ['patient' => $user->patient?->id];
        return parent::index(['available_balance' => $user->wallet]);
    }
}
