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
            'name' => config('validations.string.req'),
            'gender' => config('validations.integer.req').'|in:'. implode(',', UserGenderConstants::values()),
            'national_id' => config('validations.integer.req').'|unique:patients,national_id',
            'date_of_birth' => config('validations.date.req'),
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
}
