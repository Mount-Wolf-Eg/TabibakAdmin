<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class DoctorArticleRequest extends FormRequest
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
        $validated['author_id'] = auth()->id();
        return $validated;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules =  [
            'medical_speciality_id' => sprintf(config('validations.model.active_req'), 'medical_specialities'),
            'title.ar' => config('validations.string.req'),
            'title.en' => config('validations.string.req'),
            'content.ar' => config('validations.long_text.req'),
            'content.en' => config('validations.long_text.req'),
            'images' => config('validations.array.null'),
            'images.*' => sprintf(config('validations.model.null'), 'files')
        ];
        if ($this->isMethod('post')) {
            $rules['main_image'] = sprintf(config('validations.model.req'), 'files');
        }else{
            $rules['main_image'] = sprintf(config('validations.model.null'), 'files');
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
            'title.ar' => __('messages.title_ar'),
            'title.en' => __('messages.title_en'),
            'content.ar' => __('messages.content_ar'),
            'content.en' => __('messages.content_en'),
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
