<?php

namespace App\Http\Resources;

use App\Constants\ConsultationPatientStatusConstants;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsultationReplyResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $status = ConsultationPatientStatusConstants::from($this->pivot->status);
        $this->micro = [
            'status' => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            'amount' => $this->pivot->amount,
        ];
        $this->mini = [
            'status_can_be_changed' => $status->is(ConsultationPatientStatusConstants::PENDING),
            'reason' => $this->pivot->reason,
            'doctor_set_consultation_at' => $this->pivot->doctor_set_consultation_at,
            'created_at' => $this->pivot->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->pivot->updated_at?->format('Y-m-d H:i'),
        ];
        $this->relations = [
            'doctor' => new DoctorResource($this),
        ];
        return $this->getResource();
    }
}
