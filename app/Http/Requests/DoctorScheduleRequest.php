<?php

namespace App\Http\Requests;

use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class DoctorScheduleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return (bool) auth()->user()->doctor;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        return self::afterValidation($validated);
    }

    public static function afterValidation($validated)
    {
        if (!empty($validated['schedule_days'])) {
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
        }
        $validated['urgent_consultation_price'] = $validated['price'];
        $validated['with_appointment_consultation_price'] = $validated['price'];
        unset($validated['price']);
        return $validated;
    }

    public function rules(): array
    {
        return [
            'consultation_period' => config('validations.integer.null'). '|min:5|max:300',
            'schedule_days' => config('validations.array.null'),
            'schedule_days.*.day' => config('validations.day.req'),
            'schedule_days.*.shifts' => config('validations.array.req'),
            'schedule_days.*.shifts.*.from_time' => config('validations.time.req'),
            'schedule_days.*.shifts.*.to_time' => config('validations.time.req'),
            'schedule_repeat_from' => config('validations.date.null').'|after_or_equal:today',
            'schedule_repeat_to' => config('validations.date.null').'|after:schedule_repeat_from',
            'reminder_before_consultation' => config('validations.integer.req'),
            'price' => config('validations.integer.req')
        ];
    }
}
