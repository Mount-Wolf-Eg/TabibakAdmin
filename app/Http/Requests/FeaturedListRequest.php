<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class FeaturedListRequest extends FormRequest
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
        $validated['featured_list_title']['en'] = $this['title.en'];
        $validated['featured_list_title']['ar'] = $this['title.ar'];
        $validated['featured_list_text']['en'] = $this['text.en'];
        $validated['featured_list_text']['ar'] = $this['text.ar'];
        return $validated;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title.ar' => config('validations.string.req'),
            'title.en' => config('validations.string.req'),
            'text.ar' => config('validations.text.req'),
            'text.en' => config('validations.text.req'),
        ];
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
            'text.ar' => __('messages.text_ar'),
            'text.en' => __('messages.text_en')
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
