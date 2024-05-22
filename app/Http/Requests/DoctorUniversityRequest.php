<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorUniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) auth()->user()->doctor;
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated =  self::getValidated($validated);
        $validated['universities'][0]['doctor_university_id'] = $this->university?->id;
        return $validated;
    }

    public static function getValidated($validated): array
    {
        $validated['universities'][] = [
            'university_id' => $validated['id'],
            'academic_degree_id' => $validated['academic_degree_id'],
            'medical_speciality_id' => $validated['medical_speciality_id'],
            'certificate' => $validated['certificate']
        ];
        unset($validated['id'], $validated['academic_degree_id'], $validated['medical_speciality_id'], $validated['certificate']);
        return $validated;
    }

    public function rules(): array
    {
        return [
            'id' => sprintf(config('validations.model.active_req'), 'universities'),
            'academic_degree_id' => sprintf(config('validations.model.active_req'), 'academic_degrees'),
            'medical_speciality_id' => sprintf(config('validations.model.active_req'), 'medical_specialities'),
            'certificate' => sprintf(config('validations.model.req'), 'files'),
        ];
    }
}
