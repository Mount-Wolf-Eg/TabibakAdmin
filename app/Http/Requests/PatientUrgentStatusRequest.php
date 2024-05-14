<?php

namespace App\Http\Requests;

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
        if (!$this->route('consultation')->patientCanChangeDoctorStatusOffer(request('doctor_id')))
            abort(403, __('messages.not_allowed'));
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
