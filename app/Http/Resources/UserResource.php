<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'name' => $this->name,
            // 'doctor_is_active' => $this->doctor_is_active
        ];
        $this->mini = [
            'phone' => $this->phone,
            'email' => $this->email,
        ];
        $this->full = [
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'age' => $this->age,
            'gender' => [
                'value' => $this->gender?->value,
                'label' => $this->gender?->label(),
            ],
            $this->mergeWhen(isset($this->api_token), [
                'token' => $this->api_token,
            ]),
            'is_active' => $this->is_active,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
        $this->relations = [
            'patient' => $this->relationLoaded('patient') ? new PatientResource($this->patient) : null,
            'doctor' => $this->relationLoaded('doctor') ? new DoctorResource($this->doctor) : null,
            'avatar' => $this->relationLoaded('avatar') ? new FileResource($this->avatar) : null,
            'city' => $this->relationLoaded('city') ? new CityResource($this->city) : null,
        ];
        return $this->getResource();
    }
}
