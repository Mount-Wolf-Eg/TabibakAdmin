<?php

namespace App\Http\Requests;

use App\Constants\ConsultationTransferCaseRateConstants;
use App\Constants\ConsultationVendorTypeConstants;
use Illuminate\Foundation\Http\FormRequest;

class ConsultationVendorReferralRequest extends FormRequest
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
            'vendors.*.vendor_id' => sprintf(config('validations.model.active_req'), 'vendors'),
            'vendors.*.type' => config('validations.numeric.req') . '|in:' . implode(',', ConsultationVendorTypeConstants::values()),
            'vendors.*.transfer_case_rate' => config('validations.numeric.req'). '|in:' . implode(',', ConsultationTransferCaseRateConstants::values()),
            'vendors.*.transfer_notes' => config('validations.text.req'),
            'vendors.*.transfer_reason' => config('validations.text.req'),
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        $validated['vendors'] = array_map(function ($vendor) use($validated) {
            $vendor['transfer_case_rate'] = $validated['transfer_case_rate'];
            $vendor['transfer_notes'] = $validated['transfer_notes'];
            $vendor['transfer_reason'] = $validated['transfer_reason'];
            return $vendor;

        }, $validated['vendors']);

        return $validated;
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
