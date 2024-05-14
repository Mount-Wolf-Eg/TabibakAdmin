<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class DoctorResource extends BaseResource
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
            'national_id' => $this->national_id,
            'medical_id' => $this->medical_id,
        ];
        $this->mini = [
            'is_active' => $this->is_active,
            'active_status' => $this->active_status,
            'active_class' => $this->active_class,
            'request_status' => [
                'value' => $this->request_status?->value,
                'label' => $this->request_status?->label(),
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
        $this->full = [
            'university' => $this->university,
            'bio' => $this->bio,
            'urgent_consultation_enabled' => $this->urgent_consultation_enabled,
            'with_appointment_consultation_enabled' => $this->with_appointment_consultation_enabled,
            'experience_years' => $this->experience_years,
            'price' => $this->with_appointment_consultation_price,
            'reminder_before_consultation' => $this->reminder_before_consultation,
            'consultation_period' => $this->consultation_period,
        ];
        $this->relations = [
            'user' => $this->relationLoaded('user') ? new UserResource($this->user) : null,
            'medical_specialities' => $this->relationLoaded('medicalSpecialities') ? MedicalSpecialityResource::collection($this->medicalSpecialities) : [],
            'academic_degree' => $this->relationLoaded('academicDegree') ? new AcademicDegreeResource($this->academicDegree) : null,
            'attachments' => $this->relationLoaded('attachments') ? FileResource::collection($this->attachments) : [],
            'rates_count' => $this->relationLoaded('rates') ? $this->rates->count() : 0,
            'rates_avg' => $this->relationLoaded('rates') ? $this->rates->avg('value') : 0,
            'universities' => $this->relationLoaded('universities') ? DoctorUniversityResource::collection($this->universities) : [],
            'schedule_days' => $this->relationLoaded('scheduleDays') ? DoctorScheduleDayResource::collection($this->scheduleDays) : [],
            'first_schedule_day' => $this->relationLoaded('scheduleDays') ? new DoctorScheduleDayResource($this->scheduleDays->first()) : null,
            'last_schedule_day' => $this->relationLoaded('scheduleDays') ? new DoctorScheduleDayResource($this->scheduleDays->last()) : null,
            'hospitals' => $this->relationLoaded('hospitals') ? HospitalResource::collection($this->hospitals) : [],
            'last_hospital' => $this->relationLoaded('hospitals') ? new HospitalResource($this->hospitals->last()) : null,
            'consultations_count' => $this->relationLoaded('consultations') ? $this->consultations->count() : 0,
        ];
        return $this->getResource();
    }
}
