<?php

namespace App\Http\Requests;

use App\Constants\ConsultationStatusConstants;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class DoctorAcceptUrgentConsultationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['doctor_id'] = auth()->user()->doctor?->id;
        $data['status'] = ConsultationStatusConstants::DOCTOR_ACCEPTED_URGENT_CASE->value;
        $data['doctor_set_urgent_at'] = Carbon::parse($data['doctor_set_urgent_at']);
        return $data;
    }

    public function rules(): array
    {
        return [
            'doctor_set_urgent_at' => config('validations.datetime.req').'|after_or_equal:now',
            'amount' => config('validations.integer.req'),
        ];
    }
}
