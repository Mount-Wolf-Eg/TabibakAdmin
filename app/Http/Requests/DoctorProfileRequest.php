<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use Illuminate\Foundation\Http\FormRequest;

class DoctorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) auth()->user()->doctor;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated['user']['id'] = auth()->id();
        if (auth()->user()->doctor)
            $validated['doctor_id'] = auth()->user()->doctor->id;
        return UserRequest::prepareUserForRoles($validated, RoleNameConstants::DOCTOR->value);
    }

    public function rules(): array
    {
        return [
            'national_id' => sprintf(config('validations.integer.req_max'), 10) . '|regex:/^[1-4]/',
            'medical_id' => config('validations.string.req'),
            'date_of_birth' => config('validations.date.req'),
            'phone' => config('validations.phone.req'),
        ];
    }

    public function messages()
    {
        return [
            'national_id.regex' => trans('The national ID must start with 1, 2, 3, or 4'),
        ];
    }
}
