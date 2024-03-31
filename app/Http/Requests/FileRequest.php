<?php

namespace App\Http\Requests;

use App\Constants\FileConstants;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
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
        $validated = parent::validated();
        $validated['user_id'] = auth('sanctum')->id();
        $validated['fileable_type'] = isset($validated['fileable_id']) ? match ($validated['type']){
            FileConstants::FILE_USER_AVATAR->value => 'User',
            FileConstants::FILE_TYPE_ARTICLE_MAIN_IMAGE->value => 'Article',
            FileConstants::FILE_TYPE_CONSULTATION_ATTACHMENTS->value => 'Consultation',
            default => null
        } : null;
        return $validated;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|'.$this->getTypeValidation().'|max:51200',
            'type' => config('validations.string.req').'|in:'.implode(',', FileConstants::values()),
            'fileable_id' => config('validations.integer.null'),
        ];
    }

    public function getTypeValidation(): string|null
    {
        $mixed = $this->accept ?
            config('validations.file.mixed').','.str_replace('.','',$this->accept)
            : config('validations.file.mixed');
        return match(request('type')){
            FileConstants::FILE_USER_AVATAR->value, FileConstants::FILE_TYPE_ARTICLE_MAIN_IMAGE->value
            => config('validations.file.image'),
            default => $mixed
        };
    }

    public function messages(): array
    {
        return [
            'file.max' => __('validation.max.file', ['max' => $this->maxSize]),
        ];
    }
}
