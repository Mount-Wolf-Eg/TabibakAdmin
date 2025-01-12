<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class PatientRequest extends FormRequest
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
        if ($this->route('patient')) {
            $validated['user']['id'] = $this->route('patient')->user_id;
        }
        return UserRequest::prepareUserForRoles($validated, RoleNameConstants::PATIENT->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => config('validations.string.req'),
            'date_of_birth' => config('validations.date.req').'|before_or_equal:today',
            'phone' => config('validations.phone.req').'|unique:users,phone,'.$this->route('patient')?->user_id,
            'national_id' => sprintf(config('validations.integer.null_max'), 10).'|unique:patients,national_id,'.$this->route('patient')?->id . '|regex:/^[1-4]/',
        ];
        if ($this->getMethod() === 'POST' && !auth()->user()->patient) {
            $rules['password'] = config('validations.password.req');
        }else{
            $rules['password'] = config('validations.password.null');
        }
        return $rules;
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
            'national_id' => __('messages.national_id'),
            'phone' => __('messages.phone'),
            'password' => __('messages.password'),
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'national_id.regex' => trans('The national ID must start with 1, 2, 3, or 4'),
        ];
    }
}
