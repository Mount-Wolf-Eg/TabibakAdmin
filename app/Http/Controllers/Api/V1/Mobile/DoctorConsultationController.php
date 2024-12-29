<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTypeConstants;
use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\ConsultationDoctorReferralRequest;
use App\Http\Requests\ConsultationPrescriptionRequest;
use App\Http\Requests\ConsultationVendorReferralRequest;
use App\Http\Requests\DoctorAcceptUrgentConsultationRequest;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;
use App\Services\Repositories\ConsultationDoctorReferralService;
use App\Services\Repositories\ConsultationNotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorConsultationController extends BaseApiController
{

    private ConsultationNotificationService $notificationService;
    private ConsultationDoctorReferralService $doctorReferralService;

    /**
     * PatientConsultationController constructor.
     * @param ConsultationContract $contract
     * @param ConsultationNotificationService $notificationService
     * @param ConsultationDoctorReferralService $doctorReferralService
     */
    public function __construct(ConsultationContract              $contract,
                                ConsultationNotificationService   $notificationService,
                                ConsultationDoctorReferralService $doctorReferralService)
    {
        $this->middleware('role:doctor');
        $this->defaultScopes = ['doctorsList' => true, 'doctorNoConsultationPatient' => true];
        $this->relations = ['patient.parent', 'patient.diseases', 'doctorScheduleDayShift.day', 'doctor.rates', 'attachments'];
        parent::__construct($contract, ConsultationResource::class);
        $this->notificationService = $notificationService;
        $this->doctorReferralService = $doctorReferralService;
    }

    /**
     * Display the specified resource.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function show(Consultation $consultation): JsonResponse
    {
        try {
            $this->relations = array_merge($this->relations, ['medicalSpeciality', 'vendors', 'patient.diseases']);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Update referral vendors for the consultation.
     * @param ConsultationVendorReferralRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function vendorReferral(ConsultationVendorReferralRequest $request, Consultation $consultation)
    {
        try {
            if (!$consultation->doctorCanDoVendorReferral())
                abort(403, __('messages.doctor_referral_validation', ['status' => $consultation->status->label()]));
            $consultation = $this->contract->update($consultation, $request->validated());
            $this->notificationService->vendorReferral($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Update referral vendors for the consultation.
     * @param ConsultationDoctorReferralRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function doctorReferral(ConsultationDoctorReferralRequest $request, Consultation $consultation)
    {
        try {
            if (!$consultation->doctorCanDoDoctorReferral())
                abort(403, __('messages.doctor_referral_validation', ['status' => $consultation->status->label()]));
            $consultation = $this->doctorReferralService->save($consultation, $request->validated());
            $this->notificationService->doctorReferral($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Update prescription for the consultation.
     * @param ConsultationPrescriptionRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function prescription(ConsultationPrescriptionRequest $request, Consultation $consultation)
    {
        try {
            if (!$consultation->doctorCanWritePrescription())
                abort(403, __('messages.doctor_prescription_validation', ['status' => $consultation->status->label()]));
            $consultation = $this->contract->update($consultation, $request->validated());
            $this->notificationService->prescription($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Approve medical report for the consultation.
     * @param Consultation $consultation
     * @param Request $request
     * @return JsonResponse
     */
    public function approveMedicalReport(Consultation $consultation, Request $request)
    {
        try {
            $request->validate([
                'doctor_description' => config('validations.text.req')
            ]);
            if (!$consultation->doctorCanApproveMedicalReport())
                abort(403, __('messages.doctor_approve_medical_report_validation', ['status' => $consultation->status->label()]));
            $consultation = $this->contract->update($consultation, [
                'status' => ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT->value,
                'doctor_description' => $request->input('doctor_description')
            ]);
            $this->notificationService->doctorApprovedMedicalReport($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage(), 422);
        }
    }

    /**
     * Accept urgent case for the consultation.
     * @param DoctorAcceptUrgentConsultationRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function acceptUrgentCase(DoctorAcceptUrgentConsultationRequest $request, Consultation $consultation)
    {
        try {
            if (!$consultation->doctorCanAcceptUrgentCase())
                abort(403, __('messages.not_allowed'));
            $this->contract->syncWithoutDetaching($consultation, 'replies', $request->validated());
            $consultation = $this->contract->update($consultation, ['status' => ConsultationStatusConstants::URGENT_HAS_DOCTORS_REPLIES->value]);
            $this->notificationService->doctorApprovedUrgentCase($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Cancel the consultation.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function cancel(Consultation $consultation)
    {
        try {
            if (!$consultation->doctorCanCancel())
                abort(403, __('messages.doctor_cancel_validation', ['status' => $consultation->status->label()]));
            $consultation = $this->contract->update($consultation, ['status' => ConsultationStatusConstants::DOCTOR_CANCELLED->value]);
            $this->notificationService->doctorCancel($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Reschedule the consultation.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function reschedule(Consultation $consultation)
    {
        try {
            if (!$consultation->doctorCanReschedule())
                abort(403, __('messages.doctor_cancel_validation', ['status' => $consultation->status->label()]));
            $consultation = $this->contract->update($consultation, ['status' => ConsultationStatusConstants::NEEDS_RESCHEDULE->value]);
            $this->notificationService->doctorReschedule($consultation);
            return $this->respondWithModel($consultation);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage(), 422);
        }
    }

    /**
     * Doctor statistics.
     * @return JsonResponse
     */
    public function statistics()
    {
        try {
            $calendarCases = $this->contract->freshRepo()->countWithFilters(['mineAsDoctor' => true, 'type' => ConsultationTypeConstants::WITH_APPOINTMENT->value]);
            $urgentCases = $this->contract->freshRepo()->countWithFilters(['mineAsDoctor' => true, 'type' => ConsultationTypeConstants::URGENT->value]);
            return $this->respondWithArray([
                'calendar_cases' => $calendarCases,
                'urgent_cases' => $urgentCases,
                'total_cases' => $calendarCases + $urgentCases,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}
