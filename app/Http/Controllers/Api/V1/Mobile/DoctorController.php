<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Constants\DoctorRequestStatusConstants;
use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\UserResource;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorContract;
use Illuminate\Http\JsonResponse;

class DoctorController extends BaseApiController
{
    /**
     * DoctorController constructor.
     * @param DoctorContract $contract
     */
    public function __construct(DoctorContract $contract)
    {
        parent::__construct($contract, DoctorResource::class);
        $this->defaultScopes = ['requestStatus' => DoctorRequestStatusConstants::APPROVED->value];
        $this->relations = ['rates', 'medicalSpecialities', 'city', 'attachments', 'academicDegree',
        'hospitals', 'universities.academicDegree', 'universities.certificate', 'universities.university'];
    }

    /**
     *  get specified doctor full details
     *
     * @param Doctor $doctor
     * @return JsonResponse
     */
    public function show(Doctor $doctor)
    {
        if (!$doctor->request_status?->is(DoctorRequestStatusConstants::APPROVED))
        {
            return $this->respondWithError('Doctor not found', 404);
        }
        $doctor->load('medicalSpecialities', 'city', 'attachments', 'academicDegree');
        return $this->respondWithModel($doctor);
    }
}
