<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorProfessionalStatusRequest;
use App\Http\Requests\DoctorProfileRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\DoctorContract;

class DoctorProfileController extends BaseApiController
{
    public function __construct(DoctorContract $contract)
    {
        parent::__construct($contract, UserResource::class);
    }

    public function updateMainInfo(DoctorProfileRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor');
        return $this->respondWithModel($user);
    }

    public function updateProfessionalStatus(DoctorProfessionalStatusRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $doctor = $this->contract->update($doctor, $request->validated());
        $user = $doctor->user->load('doctor.universities.university',
            'doctor.universities.academicDegree', 'doctor.universities.certificate');
        return $this->respondWithModel($user);
    }

}
