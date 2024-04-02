<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\ConsultationPrescriptionRequest;
use App\Http\Requests\ConsultationReferralRequest;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;
use Exception;
use Illuminate\Http\JsonResponse;

class DoctorConsultationController extends BaseApiController
{
    /**
     * PatientConsultationController constructor.
     * @param ConsultationContract $contract
     */
    public function __construct(ConsultationContract $contract)
    {
        $this->middleware('role:doctor');
        $this->defaultScopes = ['mineAsDoctor' => true];
        $this->relations = ['patient', 'doctorScheduleDayShift', 'doctor.rates'];
        parent::__construct($contract, ConsultationResource::class);
    }
    /**
     * Display the specified resource.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function show(Consultation $consultation): JsonResponse
    {
        try {
            if (!$consultation->isMineAsDoctor())
                abort(403, __('messages.not_allowed'));
            $this->relations = array_merge($this->relations, ['attachments', 'diseases', 'medicalSpeciality', 'vendors']);
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function referral(ConsultationReferralRequest $request, Consultation $consultation)
    {
        try {
            if (!$consultation->isMineAsDoctor())
                abort(403, __('messages.not_allowed'));
            $consultation = $this->contract->update($consultation, $request->validated());
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function prescription(ConsultationPrescriptionRequest $request,Consultation $consultation)
    {
        try {
            if (!$consultation->isMineAsDoctor())
                abort(403, __('messages.not_allowed'));
            $consultation = $this->contract->update($consultation, $request->validated());
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}
