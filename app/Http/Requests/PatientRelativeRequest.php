<?php

namespace App\Http\Requests;

use App\Constants\PatientBloodTypeConstants;
use App\Constants\RoleNameConstants;
use App\Constants\UserGenderConstants;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PatientRelativeRequest extends FormRequest
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
        $validated['parent_id'] = auth()->user()->patient->id;
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
            'national_id' => config('validations.integer.null'),
            'date_of_birth' => config('validations.date.null'),
            'phone' => config('validations.phone.null').'|unique:users,phone',
            'weight' => 'nullable|numeric|min:1|max:300',
            'height' => 'nullable|numeric|min:1|max:300',
            'blood_type' => config('validations.integer.null').'|in:'. implode(',', PatientBloodTypeConstants::values()),
            'diseases' => config('validations.array.null'),
            'diseases.*' => sprintf(config('validations.model.req'), 'diseases'),
            'latest_surgeries' => config('validations.text.null'),
            'other_diseases' => config('validations.text.null'),
        ];
    }
}
