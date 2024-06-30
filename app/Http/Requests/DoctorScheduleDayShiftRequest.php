<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\DoctorScheduleDayContract;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class DoctorScheduleDayShiftRequest extends FormRequest
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

    public function prepareForValidation(): void
    {
        if ($this->has('from_time') && $this->has('to_time')) {
            $fromTime = Carbon::parse($this->input('from_time'));
            $toTime = Carbon::parse($this->input('to_time'));
            if ($this->method() === 'POST') {
                $day = resolve(DoctorScheduleDayContract::class)->find($this->input('doctor_schedule_day_id'));
                $shifts = $day->shifts;
            } elseif ($this->method() === 'PUT') {
                $shift = $this->route('doctor_schedule_day_shift');
                $shifts = $shift->day->shifts->where('id', '!=', $shift->id);
            }
            if (!isset($shifts)) {
                return;
            }
            $conflicts = $shifts->filter(function ($item) use ($fromTime, $toTime) {
                return $fromTime->isBetween($item->from_time, $item->to_time) ||
                    $toTime->isBetween($item->from_time, $item->to_time) ||
                    ($fromTime->lt($item->from_time) && $toTime->gt($item->to_time));
            });
            if ($conflicts->isNotEmpty()) {
                abort(422, __('messages.shift_time_conflict'));
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'from_time' => config('validations.time.req'),
            'to_time' => config('validations.time.req'). '|after:from_time',
        ];
        if ($this->isMethod('post')) {
            $rules['doctor_schedule_day_id'] = sprintf(config('validations.model.req'), 'doctor_schedule_days');
        }
        return $rules;
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
