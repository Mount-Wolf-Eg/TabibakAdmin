<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Repositories\Contracts\CouponContract;
use Illuminate\Http\Request;

class CouponController extends BaseApiController
{
    public function __construct(CouponContract $contract)
    {
        parent::__construct($contract, CouponResource::class);
    }

    public function applyCoupon(Coupon $coupon, Request $request)
    {
        $request->validate([
            'medical_speciality_id' => sprintf(config('validations.model.active_req'), 'medical_specialities'),
            'amount' => config('validations.integer.req')
        ]);
        $valid = $coupon->isValidForUser(auth()->id(), request('medical_speciality_id'));
        if (!$valid) {
            return $this->respondWithError(__('messages.invalid_coupon'));
        }
        $amountAfterDiscount = $coupon->applyDiscount($request->amount);
        return $this->respondWithSuccess(__('messages.valid_coupon'), ['amount_after_discount' => $amountAfterDiscount]);
    }
}
