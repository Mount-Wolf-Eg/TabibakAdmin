<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use App\Repositories\Contracts\RoleContract;
use App\Traits\JsonValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        $validated['user']['name'] = $this['name'];
        $validated['user']['email'] = $this['email'];
        $validated['user']['phone'] = $this['phone'];
        $validated['user']['image'] = $this['image'];
        return $validated;
    }

    public function passedValidation(): void
    {
        $this->merge([
            'name' => [
                'en' => $this['name'],
                'ar' => $this['name'],
            ],
        ]);
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
            'email' => sprintf(config('validations.email.req'), 'users', 'email').','.auth()->id(),
            'phone' => config('validations.phone.req').'|unique:users,phone,'.auth()->id(),
            'image' =>  'nullable|'.config('validations.file.image') . '|mimes:jpeg,jpg,png|max:2048'
        ];

        if (isset($this['vendor_profile'])) {
            $rules['address'] = config('validations.string.null');
            $rules['services'] = config('validations.array.req');
            $rules['services.*'] = sprintf(config('validations.model.active_req'), 'vendor_services');
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
            'email' => __('messages.email'),
            'phone' => __('messages.phone'),
            'image' => __('messages.image'),
            'address' => __('messages.address'),
            'services' => __('messages.services'),
            'services.*' => __('messages.services'),
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'image.mimes' => __('validation.profile_mimes'),
        ];
    }
}
