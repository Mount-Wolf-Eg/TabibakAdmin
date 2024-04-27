<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use App\Constants\UserGenderConstants;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PatientRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        return UserRequest::prepareUserForRoles($validated, RoleNameConstants::PATIENT->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => config('validations.string.req'),
            'gender' => config('validations.integer.null').'|in:'. implode(',', UserGenderConstants::values()),
            'national_id' => config('validations.integer.req'),
            'date_of_birth' => config('validations.date.req'),
            'phone' => config('validations.phone.req').'|unique:users,phone',
            'image' => sprintf(config('validations.model.null'), 'files')
        ];
    }
}
