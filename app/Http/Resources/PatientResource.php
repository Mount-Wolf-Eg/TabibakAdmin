<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class PatientResource extends BaseResource
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
        ];
        $this->mini = [
            'is_active' => $this->is_active,
            'active_status' => $this->active_status,
            'active_class' => $this->active_class,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
        $this->full = [
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'age' => $this->age,
            'national_id' => $this->national_id,
            'social_status' => [
                'value' => $this->social_status?->value,
                'label' => $this->social_status?->label(),
            ],
            'weight' => $this->weight,
            'height' => $this->height,
            'blood_type' => [
                'value' => $this->blood_type?->value,
                'label' => $this->blood_type?->label(),
            ],
            'other_diseases' => $this->other_diseases,
            'latest_surgeries' => $this->latest_surgeries
        ];
        $this->relations = [
            'user' => $this->relationLoaded('user') ? new UserResource($this->user) : '',
            'diseases' => $this->relationLoaded('diseases') ? DiseaseResource::collection($this->diseases) : '',
        ];
        return $this->getResource();
    }
}
