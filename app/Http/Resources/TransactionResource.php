<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id'             => (int) $this->id,
            'balance_before' => (float) $this->balance_before,
            'balance_after'  => (float) $this->balance_after,
            'amount'         => (float) $this->amount,
            'type'           => $this->type,
            'type_text'      => __('transaction.type.'.$this->type),
            'status'         => $this->status,
            'status_text'    => __('transaction.status.'.$this->status),
            'reference'      => $this->reference,
            // 'modelable'      => $this->modelable,
            'bank_name'      => $this->bank_name,
            'iban'           => $this->iban,
            'created_at'     => $this->created_at?->translatedFormat('l j F Y, h:iA')
            // 'created_at'     => $this->created_at->timestamp
        ];
    }
}
