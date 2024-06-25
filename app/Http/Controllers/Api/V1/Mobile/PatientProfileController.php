<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\PatientMedicalRecordsRequest;
use App\Http\Requests\PatientProfileRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\PatientContract;
use App\Repositories\Contracts\UserContract;

class PatientProfileController extends BaseApiController
{
    public function __construct(PatientContract $contract)
    {
        parent::__construct($contract, UserResource::class);
    }

    public function updateMainInfo(PatientProfileRequest $request)
    {
        $patient = auth()->user()->patient;
        $patient = $this->contract->update($patient, $request->validated());
        $user = $patient->user->load('patient');
        return $this->respondWithModel($user);
    }

    public function updateMedicalRecords(PatientMedicalRecordsRequest $request)
    {
        $patient = auth()->user()->patient;
        $patient = $this->contract->update($patient, $request->validated());
        $user = $patient->user->load('patient.diseases');
        return $this->respondWithModel($user);
    }

    public function deactivate()
    {
        $user = auth()->user();
        $patient = $user->patient;
        $this->contract->toggleField($patient, 'is_active');
        resolve(UserContract::class)->toggleField($user, 'is_active');
        return $this->respondWithSuccess(__('messages.actions_messages.update_success'));
    }

}
