<?php

namespace App\Http\Requests;

use App\Constants\ConsultationTransferCaseRateConstants;
use Illuminate\Foundation\Http\FormRequest;

class ConsultationReferralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transfer_reason' => config('validations.text.req'),
            'transfer_notes' => config('validations.text.req'),
            'transfer_case_rate' => config('validations.numeric.req'). '|in:' . implode(',', ConsultationTransferCaseRateConstants::values()),
            'vendors' => config('validations.array.req'),
            'vendors.*' => sprintf(config('validations.model.active_req'), 'vendors'),
        ];
    }

    /**
     * Customizing input names displayed for user
     * @return array
     */
    public function attributes() : array
    {
        return [
            'transfer_reason' => __('messages.transfer_reason'),
            'transfer_notes' => __('messages.transfer_notes'),
            'transfer_case_rate' => __('messages.transfer_case_rate'),
            'vendors' => __('messages.vendors'),
        ];
    }
}
