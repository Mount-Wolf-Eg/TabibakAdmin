<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'           => (int) $this->id,
            'balance'      => (double) $this->balance,
            'transactions' => $this->when($this->relationLoaded('transactions'), TransactionResource::collection($this->transactions))
        ];
    }
}
