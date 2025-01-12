<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use App\Constants\UserGenderConstants;
use App\Traits\JsonValidationTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PatientRegisterRequest extends FormRequest
{
    use JsonValidationTrait;
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
            'name' => 'required|string|min:3|max:250|regex:/^(\b[\pL\pM]+\b\s+){2}\b[\pL\pM]+\b$/u',
            'gender' => config('validations.integer.req').'|in:'. implode(',', UserGenderConstants::values()),
            'national_id' => sprintf(config('validations.integer.req_max'), 10).'|unique:patients,national_id' . '|regex:/^[1-4]/',
            // 'date_of_birth' => config('validations.date.req'),
            'date_of_birth' => sprintf(config('validations.date.req_after'), '1989-12-31'),
            'phone' => config('validations.phone.req').'|unique:users,phone',
            'city_id' => sprintf(config('validations.model.req'), 'cities'),
            'image' => sprintf(config('validations.model.null'), 'files')
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.name'),
            'gender' => __('attributes.gender'),
            'national_id' => __('attributes.national_id'),
            'date_of_birth' => __('attributes.date_of_birth'),
            'phone' => __('attributes.phone'),
            'city_id' => __('attributes.city_id'),
            'image' => __('attributes.image')
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('The name field is required'),
            'name.regex' => trans('The name must consist of exactly three words'),
            'national_id.regex' => trans('The national ID must start with 1, 2, 3, or 4'),
        ];
    }
}
