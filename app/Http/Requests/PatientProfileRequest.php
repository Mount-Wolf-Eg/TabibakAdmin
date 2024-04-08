<?php

namespace App\Http\Requests;

use App\Constants\PatientSocialStatusConstants;
use App\Constants\RoleNameConstants;
use App\Constants\UserGenderConstants;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class PatientProfileRequest extends FormRequest
{
    use JsonValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated['user']['id'] = auth()->id();
        if (auth()->user()->patient)
            $validated['patient_id'] = auth()->user()->patient->id;
        return UserRequest::prepareUserForRoles($validated, RoleNameConstants::PATIENT->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => config('validations.string.req'),
            'gender' => config('validations.integer.null').'|in:'. implode(',', UserGenderConstants::values()),
            'date_of_birth' => config('validations.date.req'),
            'phone' => config('validations.phone.req').'|unique:users,phone,'.auth()->id(),
            'national_id' => config('validations.string.null').'|unique:patients,national_id,'.auth()->user()->patient?->id,
            'password' => config('validations.password.null'),
            'social_status' => config('validations.integer.null').'|in:'.implode(',', PatientSocialStatusConstants::values()),
        ];
    }

    /**
     * Customizing input names displayed for user
     * @return array
     */
    public function attributes() : array
    {
        return [
            'name' => __('messages.name'),
            'date_of_birth' => __('messages.date_of_birth'),
            'phone' => __('messages.phone'),
            'national_id' => __('messages.national_id'),
            'password' => __('messages.password'),
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [];
    }
}
