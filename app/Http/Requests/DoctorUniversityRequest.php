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
        return self::getValidated($validated);
    }

    public static function getValidated($validated): array
    {
        if (isset($validated['doctor_universities']))
        {
            $validated['universities'] = [];
            collect($validated['doctor_universities'])->each(function ($university) use (&$validated) {
                $validated['universities'][] = [
                    'university_id' => $university['id'],
                    'academic_degree_id' => $university['academic_degree_id'],
                    'medical_speciality_id' => $university['medical_speciality_id'],
                    'certificate' => $university['certificate']
                ];
            })->toArray();
            unset($validated['doctor_universities']);
        }
        return $validated;
    }

    public function rules(): array
    {
        return [
            'doctor_universities' => config('validations.array.null'),
            'doctor_universities.*.id' => sprintf(config('validations.model.active_req'), 'universities'),
            'doctor_universities.*.academic_degree_id' => sprintf(config('validations.model.active_req'), 'academic_degrees'),
            'doctor_universities.*.medical_speciality_id' => sprintf(config('validations.model.active_req'), 'medical_specialities'),
            'doctor_universities.*.certificate' => sprintf(config('validations.model.req'), 'files'),
        ];
    }
}
