<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationPrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prescription' => config('validations.array.req'),
            'prescription.*.name' => config('validations.string.req'),
            'prescription.*.dosage' => config('validations.string.req'),
            'prescription.*.quantity' => config('validations.string.req'),
            'prescription.*.strength' => config('validations.string.req'),
            'prescription.*.time' => config('validations.string.req').'|in:after_meal,before_meal',
        ];
    }

    /**
     * Customizing input names displayed for user
     * @return array
     */
    public function attributes() : array
    {
        return [
            'prescription' => __('messages.prescription'),
        ];
    }
}
