<?php

namespace App\Http\Requests;

use App\Constants\ConsultationContactTypeConstants;
use App\Constants\ConsultationPaymentTypeConstants;
use App\Constants\ConsultationTypeConstants;
use App\Constants\ReminderConstants;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\DoctorScheduleDayShiftContract;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class ConsultationRequest extends FormRequest
{
    use JsonValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool) auth()->user()->patient;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated['patient_id'] = $validated['patient_id'] ?? $this->user()->patient?->id;
        if ($validated['type'] == ConsultationTypeConstants::WITH_APPOINTMENT->value) {
            $validated['amount'] = resolve(DoctorContract::class)->find($validated['doctor_id'])->with_appointment_consultation_price;
            $scheduleSlot = resolve(DoctorScheduleDayShiftContract::class)
                ->find($validated['doctor_schedule_day_shift_id']);
            $scheduleDay = $scheduleSlot->day;
            $scheduleTime = $scheduleDay->date->format('Y-m-d') . ' ' . $scheduleSlot->from_time->format('H:i:s');
            $scheduleTime = Carbon::parse($scheduleTime);
            $validated['reminder_at'] = $scheduleTime->subMinutes($validated['reminder_before']);
            unset($validated['reminder_before']);
        }
        return $validated;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'patient_id' => sprintf(config('validations.model.active_null'), 'patients'),
            'doctor_id' => sprintf(config('validations.model.active_null'), 'doctors')
                .'|required_if:type,==,'.ConsultationTypeConstants::WITH_APPOINTMENT->value,
            'patient_description' => config('validations.text.req'),
            'attachments' => config('validations.array.null'),
            'attachments.*' => sprintf(config('validations.model.req'), 'files'),
            'type' => config('validations.integer.req').'|in:'.implode(',', ConsultationTypeConstants::values()),
            'doctor_schedule_day_shift_id' => 'required_if:type,==,'.ConsultationTypeConstants::WITH_APPOINTMENT->value.'|'.
                sprintf(config('validations.model.null'), 'doctor_schedule_day_shifts', 'id'),
            'contact_type' => config('validations.integer.null').'|required_if:type,==,'.ConsultationTypeConstants::URGENT->value
                .'|in:'. implode(',', ConsultationContactTypeConstants::values()),
            'reminder_before' => 'required_if:type,==,'.ConsultationTypeConstants::WITH_APPOINTMENT->value.'|'.
                config('validations.integer.null').'|in:'. implode(',', ReminderConstants::values()),
            'payment_type' => config('validations.integer.req').'|in:'. implode(',', ConsultationPaymentTypeConstants::values())
        ];
    }

    /**
     * Customizing input names displayed for user
     * @return array
     */
    public function attributes() : array
    {
        return [];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [];
    }
}
