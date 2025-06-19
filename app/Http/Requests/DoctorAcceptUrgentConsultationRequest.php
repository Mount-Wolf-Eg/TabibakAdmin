<?php

namespace App\Http\Requests;

use App\Constants\ConsultationStatusConstants;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class DoctorAcceptUrgentConsultationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return (bool) auth()->user()->doctor;
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();
        return [
            auth()->user()->doctor->id => [
                'doctor_set_consultation_at' => Carbon::parse($data['doctor_set_consultation_at']),
                'amount' => $data['amount'],
            ]
        ];
    }

    public function rules(): array
    {
        return [
            'doctor_set_consultation_at' => config('validations.datetime.req').'|after_or_equal:now',
            'amount' => config('validations.numeric.req') . '|min:0|max:100000',
        ];
    }
}
