<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class ConsultationResource extends BaseResource
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
            'patient_description' => $this->patient_description,
            'doctor_description' => $this->doctor_description,
            'medical_review' => $this->medical_review,
            'prescription' => $this->prescription,
            'amount' => $this->amount,
            'contact_type' => [
                'value' => $this->contact_type?->value,
                'label' => $this->contact_type?->label(),
            ],
            'payment_type' => [
                'value' => $this->payment_type?->value,
                'label' => $this->payment_type?->label(),
            ],
            'transfer_case_rate' => [
                'value' => $this->transfer_case_rate?->value,
                'label' => $this->transfer_case_rate?->label(),
            ],
            'reminder_at' => $this->reminder_at?->format('Y-m-d H:i:s'),
            'transfer_reason' => $this->transfer_reason,
            'transfer_notes' => $this->transfer_notes,
            'is_mine_as_patient' => $this->isMineAsPatient(),
            'is_mine_as_doctor' => $this->isMineAsDoctor(),
            'doctor_can_do_referral' => $this->doctorCanDoReferral(),
            'doctor_can_write_prescription' => $this->doctorCanWritePrescription(),
            'doctor_can_approve_medical_report' => $this->doctorCanApproveMedicalReport(),
            'doctor_can_cancel' => $this->doctorCanCancel(),
            'doctor_can_accept_urgent_case' => $this->doctorCanAcceptUrgentCase(),
        ];
        $this->relations = [
            'attachments' => $this->relationLoaded('attachments') ? FileResource::collection($this->attachments) : [],
            'patient' => $this->relationLoaded('patient') ? new PatientResource($this->patient) : null,
            'doctor' => $this->relationLoaded('doctor') ? new DoctorResource($this->doctor) : null,
            'vendors' => $this->relationLoaded('vendors') ? VendorResource::collection($this->vendors) : [],
            'medicalSpeciality' => $this->relationLoaded('medicalSpeciality') ? new MedicalSpecialityResource($this->medicalSpeciality) : null,
            'doctorScheduleDayShift' => $this->relationLoaded('doctorScheduleDayShift') ? new DoctorScheduleDayShiftResource($this->doctorScheduleDayShift) : null,
            'replies' => $this->relationLoaded('replies') ? ConsultationReplyResource::collection($this->replies) : [],
        ];
        return $this->getResource();
    }
}
