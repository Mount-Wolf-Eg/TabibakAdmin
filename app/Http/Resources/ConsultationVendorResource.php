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
            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
            ],
        ];

        $this->mini = [
            'is_active' => $this->is_active,
            'active_status' => $this->active_status,
            'active_class' => $this->active_class,
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
            'attachments' => $this->relationLoaded('attachments') ? FileResource::collection($this->attachments) : [],
            'patient' => $this->relationLoaded('patient') ? new PatientResource($this->patient) : null,
            'vendors' => $this->relationLoaded('vendors') ? VendorResource::collection($this->vendors) : [],
            'consultation' => $this->relationLoaded('consultation') ? new ConsultationResource($this->consultation) : null,
        ];

        return $this->getResource();
    }
}
