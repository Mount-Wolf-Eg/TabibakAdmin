<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use App\Repositories\Contracts\RoleContract;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class VendorRequest extends FormRequest
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
        if ($this->route('vendor')) {
            $validated['user']['id'] = $this->route('vendor')->user_id;
        }
        return UserRequest::prepareUserForRoles($validated, RoleNameConstants::VENDOR->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules =  [
            'vendor_type_id' => sprintf(config('validations.model.active_req'), 'vendor_types'),
            'name' => config('validations.string.req'),
            'email' => sprintf(config('validations.email.req'), 'users', 'email').','.$this->route('vendor')?->user_id,
            'phone' => config('validations.phone.req').'|unique:users,phone,'.$this->route('vendor')?->user_id,
            'address' => config('validations.string.null'),
            'services' => config('validations.array.req'),
            'services.*' => sprintf(config('validations.model.active_req'), 'vendor_services'),
        ];
        if($this->method() === 'POST'){
            $rules['password'] = config('validations.password.req');
            $rules['icon'] = 'required|'.config('validations.file.image').'|mimes:jpeg,jpg,png|max:2048';
        }else{
            $rules['password'] = config('validations.password.null');
            $rules['icon'] = 'nullable|'.config('validations.file.image').'|mimes:jpeg,jpg,png|max:2048';
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
            'vendor_type_id' => __('messages.type'),
            'address' => __('messages.address'),
            'services' => __('messages.services'),
            'services.*' => __('messages.services'),
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
