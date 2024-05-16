<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorProfessionalStatusRequest;
use App\Http\Requests\DoctorProfileRequest;
use App\Http\Requests\DoctorScheduleRequest;
use App\Http\Requests\DoctorUniversityRequest;
use App\Http\Resources\UserResource;
use App\Models\DoctorUniversity;
use App\Repositories\Contracts\DoctorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorProfileController extends BaseApiController
{

    /**
     * DoctorProfileController constructor.
     * @param DoctorContract $contract
     */
    public function __construct(DoctorContract $contract)
    {
        parent::__construct($contract, UserResource::class);
    }

    /**
     * @param DoctorProfileRequest $request
     * @return JsonResponse
     */
    public function updateMainInfo(DoctorProfileRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor');
        return $this->respondWithModel($user);
    }

    /**
     * @param DoctorProfessionalStatusRequest $request
     * @return JsonResponse
     */
    public function updateProfessionalStatus(DoctorProfessionalStatusRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor.universities.university', 'doctor.hospitals',
            'doctor.universities.academicDegree', 'doctor.universities.certificate',
            'doctor.universities.medicalSpeciality');
        return $this->respondWithModel($user);
    }

    public function updateSchedule(DoctorScheduleRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor.scheduleDays.shifts.availableSlots');
        return $this->respondWithModel($user);
    }

    public function addUniversity(DoctorUniversityRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor.universities.university', 'doctor.hospitals',
            'doctor.universities.academicDegree', 'doctor.universities.certificate',
            'doctor.universities.medicalSpeciality');
        return $this->respondWithModel($user);
    }

    public function updateUniversity(DoctorUniversityRequest $request, DoctorUniversity $university)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor.universities.university', 'doctor.hospitals',
            'doctor.universities.academicDegree', 'doctor.universities.certificate',
            'doctor.universities.medicalSpeciality');
        return $this->respondWithModel($user);
    }

    public function deleteUniversity(DoctorUniversity $university)
    {
        $doctor = auth()->user()->doctor;
        $doctor->universities()->delete($university);
        $user = $doctor->user->load('doctor.universities.university', 'doctor.hospitals',
            'doctor.universities.academicDegree', 'doctor.universities.certificate',
            'doctor.universities.medicalSpeciality');
        return $this->respondWithModel($user);
    }

    public function deactivate()
    {
        $doctor = auth()->user()->doctor;
        $this->contract->toggleField($doctor, 'is_active');
        return $this->respondWithSuccess(__('messages.actions_messages.update_success'));
    }

}
