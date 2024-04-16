<?php

namespace App\Http\Requests;

use App\Constants\RoleNameConstants;
use App\Repositories\Contracts\RoleContract;
use App\Traits\JsonValidationTrait;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class DoctorRegisterRequest extends FormRequest
{
    use JsonValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['schedule_days'] = collect(CarbonPeriod::between(request('schedule_repeat_from'), request('schedule_repeat_to')))->map(function ($date) {
            $dayName = strtolower($date->format('l'));
            if (in_array($dayName, array_column(request('schedule_days'), 'day'))) {
                return [
                    'date' => $date->format('Y-m-d'),
                    'shifts' => collect(request('schedule_days'))->firstWhere('day', $dayName)['shifts'],
                ];
            }
            return null;
        })->whereNotNull()->values()->toArray();
        $validated['user_id'] = auth()->id();
        $validated['role'] = resolve(RoleContract::class)->findBy('name', RoleNameConstants::DOCTOR->value);
        $validated['urgent_consultation_price'] = $validated['price'];
        $validated['with_appointment_consultation_price'] = $validated['price'];
        unset($validated['price']);
        return $validated;
    }

    public function rules(): array
    {
        return [
            'specialities' => config('validations.array.req'),
            'specialities.*' => sprintf(config('validations.model.active_req'), 'medical_specialities'),
            'academic_degree_id' => sprintf(config('validations.model.active_req'), 'academic_degrees'),
            'national_id' => config('validations.string.req'),
            'medical_id' => config('validations.string.req'),
            'city_id' => sprintf(config('validations.model.active_req'), 'cities'),
            'experience_years' => config('validations.integer.req'),
            'bio' => config('validations.string.req'),
            'attachments' => config('validations.array.null'),
            'attachments.*' => sprintf(config('validations.model.req'), 'files'),
            'urgent_consultation_enabled' => config('validations.boolean.req'),
            'with_appointment_consultation_enabled' => config('validations.boolean.req'),
            'consultation_period' => config('validations.integer.req'),
            'schedule_days' => config('validations.array.req'),
            'schedule_days.*.day' => config('validations.day.req'),
            'schedule_days.*.shifts' => config('validations.array.req'),
            'schedule_days.*.shifts.*.from_time' => config('validations.time.req'),
            'schedule_days.*.shifts.*.to_time' => config('validations.time.req'),
            'schedule_repeat_from' => config('validations.date.req').'|after_or_equal:today',
            'schedule_repeat_to' => config('validations.date.req').'|after:schedule_repeat_from',
            'reminder_before_consultation' => config('validations.integer.req'),
            'price' => config('validations.integer.req')
        ];
    }

}
