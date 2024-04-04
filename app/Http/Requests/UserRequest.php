<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use App\Repositories\Contracts\RoleContract;
use App\Traits\JsonValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $validated['name.ar'] = $this['name'];
        $validated['name.en'] = $this['name'];
        return $validated;
    }


    public static function prepareUserForRoles($validated, $role): array
    {
        $validated['user']['name']['ar'] = $validated['name'] ?? null;
        $validated['user']['name']['en'] = $validated['name'] ?? null;
        $validated['user']['email'] = $validated['email'] ?? null;
        $validated['user']['phone'] = $validated['phone'] ?? null;
        $validated['user']['image'] = $validated['image'] ?? null;
        $validated['user']['password'] = $validated['password'] ?? null;
        $validated['user']['role_id'] = resolve(RoleContract::class)->findBy('name', $role)?->id;
        unset($validated['name'], $validated['email'], $validated['phone'], $validated['password']);
        return $validated;
    }

    public function passedValidation(): void
    {
        $this->merge([
            'name' => [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
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
            'email' => sprintf(config('validations.email.req'), 'users', 'email').','.$this->route('user')?->id,
            'phone' => config('validations.phone.req').'|unique:users,phone,'.$this->route('user')?->id,
            'image' =>  'nullable|'.config('validations.file.image') . '|mimes:jpeg,jpg,png|max:2048',
            'role_id' =>  sprintf(config('validations.model.active_req'), 'roles')
        ];

        if ($this->getMethod() === 'POST') {
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
            'email' => __('messages.email'),
            'phone' => __('messages.phone'),
            'image' => __('messages.image'),
            'role_id' => __('messages.role'),
            'password' => __('messages.password'),
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'image.image' => __('validation.profile_mimes'),
            'image.mimes' => __('validation.profile_mimes'),
        ];
    }
}
