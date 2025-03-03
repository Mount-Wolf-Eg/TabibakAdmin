<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\DoctorScheduleDayContract;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\JsonValidationTrait;

class DoctorScheduleDayRequest extends FormRequest
{
    use JsonValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool) auth()->user()->doctor;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated['doctor_id'] = auth()->user()->doctor->id;
        return $validated;
    }

    public function prepareForValidation(): void
    {
        $doctor = auth()->user()->doctor;
        if ($this->method() === 'POST' && request('date')){
            $day = resolve(DoctorScheduleDayContract::class)->findByFilters(
                ['date' => Carbon::parse(request('date')), 'doctor' => $doctor->id]);
            if ($day){
                abort(422, __('messages.date_already_exists'));
            }
        }
        if ($this->method() === 'PUT'){
            $day = $this->route('doctor_schedule_day');
            if ($day->doctor_id !== $doctor->id){
                abort(422, __('messages.not_allowed'));
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
        return [
            'date' => config('validations.date.req')."|after:today",
            'shifts' => config('validations.array.req'),
            'shifts.*.from_time' => config('validations.time.req'),
            'shifts.*.to_time' => config('validations.time.req')
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
