<?php

namespace App\Http\Requests;

use App\Constants\ConsultationTransferCaseRateConstants;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\DoctorScheduleDayShiftContract;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class ConsultationDoctorReferralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        if (request('doctor_schedule_day_shift_id') === null && request('doctor_id') === null) {
            return;
        }
        $doctor = resolve(DoctorContract::class)->find(request('doctor_id'));
        $doctor->load('scheduleDaysShifts.day');
        $scheduleSlot = $doctor->scheduleDaysShifts->where('id', request('doctor_schedule_day_shift_id'))->first();
        if (!$scheduleSlot) {
            throw new ValidationException(__('messages.schedule_slot_not_related_to_doctor'));
        }
        $actualTime = Carbon::parse($scheduleSlot->day->date->format('Y-m-d')
            . ' ' . $scheduleSlot->from_time->format('H:i:s'));
        if ($actualTime->isPast()) {
            throw new ValidationException(__('messages.schedule_slot_expired'));
        }
    }

    public function rules(): array
    {
        return [
            'doctor_id' => sprintf(config('validations.model.active_req'), 'doctors'),
            'doctor_schedule_day_shift_id' => sprintf(config('validations.model.req'),
                'doctor_schedule_day_shifts')
        ];
    }

    /**
     * Customizing input names displayed for user
     * @return array
     */
    public function attributes() : array
    {
        return [
            'doctor_id' => __('messages.doctor'),
            'doctor_schedule_day_shift_id' => __('messages.doctor_schedule_day_shift_id')
        ];
    }
}
