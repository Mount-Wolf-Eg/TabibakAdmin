<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class PaymentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request) : array
    {
        $this->micro = [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
        ];
        $this->mini = [
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ],
            'payment_method' => [
                'value' => $this->payment_method->value,
                'label' => $this->payment_method->label(),
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
        $this->full = [
            'metadata' => $this->metadata,
            'app_percentage' => $this->app_percentage,
            'doctor_percentage' => $this->doctor_percentage,
        ];
        $this->relations = [
            'payer' => $this->relationLoaded('payer')? new UserResource($this->payer) : null,
            'beneficiary' => $this->relationLoaded('beneficiary')? new UserResource($this->beneficiary) : null,
            'currency' => $this->relationLoaded('currency')? new CurrencyResource($this->currency) : null,
            'consultation' => $this->relationLoaded('payable')? new ConsultationResource($this->payable) : null,
            'coupon' => $this->relationLoaded('coupon')? new CouponResource($this->coupon) : null,
        ];
        return $this->getResource();
    }
}
