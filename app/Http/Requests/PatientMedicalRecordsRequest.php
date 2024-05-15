<?php

namespace App\Http\Requests;

use App\Constants\PatientBloodTypeConstants;
use Illuminate\Foundation\Http\FormRequest;

class PatientMedicalRecordsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|min:1|max:300',
            'height' => 'required|numeric|min:1|max:300',
            'blood_type' => config('validations.integer.req').'|in:'. implode(',', PatientBloodTypeConstants::values()),
            'diseases' => config('validations.array.null'),
            'diseases.*' => sprintf(config('validations.model.req'), 'diseases'),
            'latest_surgeries' => config('validations.text.null'),
            'other_diseases' => config('validations.text.null'),
        ];
    }

    /**
     * Customizing input names displayed for user
     * @return array
     */
    public function attributes() : array
    {
        return [
            'weight' => __('messages.weight'),
            'height' => __('messages.height'),
            'blood_type' => __('messages.blood_type'),
            'diseases' => __('messages.diseases'),
            'latest_surgeries' => __('messages.latest_surgeries'),
            'other_diseases' => __('messages.other_diseases'),
        ];
    }
}
