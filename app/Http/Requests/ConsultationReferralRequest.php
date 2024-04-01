<?php

namespace App\Http\Requests;

use App\Constants\ConsultationTransferCaseRateConstants;
use Illuminate\Foundation\Http\FormRequest;

class ConsultationReferralRequest extends FormRequest
{
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

    public function authorize(): bool
    {
        return true;
    }
}
