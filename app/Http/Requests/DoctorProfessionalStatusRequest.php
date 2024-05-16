<?php

namespace App\Http\Requests;

use App\Models\Hospital;
use App\Repositories\Contracts\HospitalContract;
use Illuminate\Foundation\Http\FormRequest;

class DoctorProfessionalStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) auth()->user()->doctor;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        if (isset($validated['doctor_hospitals']))
        {
            $validated['hospitals'] = [];
            collect($validated['doctor_hospitals'])->each(function ($hospital) use (&$validated){
                info($hospital);
                $hospitalModel = resolve(HospitalContract::class)->freshRepo()->search(['name' => $hospital['name']])->first();
                if (!$hospitalModel) {
                    $hospitalModel = resolve(HospitalContract::class)->create(['name' => $hospital['name']]);
                }
                $validated['hospitals'][$hospitalModel->id] = [
                    'start_date' => $hospital['start_date'],
                    'end_date' => $hospital['end_date'] ?? null
                ];
            })->toArray();
            unset($validated['doctor_hospitals']);
        }
        return array_merge($validated, DoctorUniversityRequest::getValidated($validated));
    }

    public function rules(): array
    {
        $rules =  [
            'bio' => config('validations.long_text.req'),
            'experience_years' => config('validations.tiny_int.req'),
            'doctor_hospitals' => config('validations.array.null'),
            'doctor_hospitals.*.name' => config('validations.string.req'),
            'doctor_hospitals.*.start_date' => config('validations.date.req').'|before_or_equal:today',
            'doctor_hospitals.*.end_date' => config('validations.date.null').'|after_or_equal:doctor_hospitals.*.start_date',
        ];
        return array_merge($rules, (new DoctorUniversityRequest())->rules());
    }
}
