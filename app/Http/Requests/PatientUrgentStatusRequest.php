<?php

namespace App\Http\Requests;

use App\Constants\ConsultationPatientStatusConstants;
use Illuminate\Foundation\Http\FormRequest;

class PatientUrgentStatusRequest extends FormRequest
{
    protected int $status;

    public function __construct(int $status)
    {
        parent::__construct();
        $this->status = $status;
    }

    public function authorize(): bool
    {
        return (boolean) auth()->user()->patient;
    }

    public function prepareForValidation(): void
    {
        $consultation = $this->route('consultation');
        $doctorId = request('doctor_id');
        if (!$consultation->patientCanChangeDoctorStatusOffer($doctorId))
        {
            $reply = $consultation->replies->where('id', $doctorId)->first()?->pivot;
            if (!$reply) {
                abort(403, __('messages.patient_change_consultation_reply_status_validation',
                    [
                        'status' => $consultation->status->label(),
                        'reply' => ''
                    ]
                ));
            }
            $replyStatus = ConsultationPatientStatusConstants::tryFrom($reply->status);
            abort(403, __('messages.patient_change_consultation_reply_status_validation',
                [
                    'status' => $consultation->status->label(),
                    'reply' => $replyStatus->label()
                ]
            ));
        }
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['doctor_id'] = (int) $data['doctor_id'];
        $data['amount'] = $this->route('consultation')->replies->where('id', $data['doctor_id'])->first()->pivot->amount;
        $data['replies'] = [
            $data['doctor_id'] => [
                'status' => $this->status,
                'reason' => $data['reason'] ?? null,
            ],
        ];
        return $data;
    }
    public function rules(): array
    {
        $consultation = $this->route('consultation');
        $doctorIds = $consultation->replies?->pluck('pivot.doctor_id')->toArray();
        return [
            'doctor_id' => 'required|in:' . implode(',', $doctorIds),
            'reason' => config('validations.text.null')
        ];
    }
}
