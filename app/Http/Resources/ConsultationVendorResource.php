<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ConsultationVendorResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->micro = [
            'id' => $this->id,

            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ],

            'required' => [
                'value' => empty($this->attachments),
                'lable' => __('messages.' . (empty($this->attachments) ? 'required' : 'added'))
            ],

            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
            ],
        ];

        $this->mini = [
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];

        $this->full = [
            'transfer_case_rate' => [
                'value' => $this->transfer_case_rate?->value,
                'label' => $this->transfer_case_rate?->label(),
            ],
            'transfer_reason' => $this->transfer_reason,
            'transfer_notes' => $this->transfer_notes
        ];

        $this->relations = [
            'attachments' => FileResource::collection($this->attachments),
            'vendor' => VendorResource::make($this->vendor),
        ];

        return $this->getResource();
    }
}
