<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class CouponResource extends BaseResource
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
            'code' => $this->code,
        ];
        $this->mini = [
            'valid_from' => $this->valid_from?->format('Y-m-d H:i'),
            'valid_to' => $this->valid_to?->format('Y-m-d H:i'),
            'is_active' => $this->is_active,
            'active_status' => $this->active_status,
            'active_class' => $this->active_class,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
        $this->full = [
            'description' => $this->description,
            'discount_type' => [
                'value' => $this->discount_type->value,
                'label' => $this->discount_type->label(),
            ],
            'discount_amount_txt' => $this->discount_amount_txt,
            'discount_amount' => $this->discount_amount,
            'status' => $this->status,
        ];
        $this->relations = [
        ];
        return $this->getResource();
    }
}
