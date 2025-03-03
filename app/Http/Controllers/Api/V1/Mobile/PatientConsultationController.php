<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTransferCaseRateConstants;
use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\ConsultationRequest;
use App\Http\Requests\ConsultationVendorFileRequest;
use App\Http\Requests\PatientUrgentApproveRequest;
use App\Http\Requests\PatientUrgentRejectRequest;
use App\Http\Resources\ConsultationResource;
use App\Http\Resources\DoctorScheduleDayShiftResource;
use App\Http\Resources\FileResource;
use App\Models\Consultation;
use App\Models\ConsultationVendor;
use App\Repositories\Contracts\ConsultationContract;
use App\Repositories\Contracts\DoctorContract;
use App\Services\Repositories\ConsultationNotificationService;
use Exception;
use Illuminate\Http\JsonResponse;

class PatientConsultationController extends BaseApiController
{
    private ConsultationNotificationService $notificationService;

    /**
     * PatientConsultationController constructor.
     * @param ConsultationContract $contract
     * @param ConsultationNotificationService $notificationService
     */
    public function __construct(ConsultationContract $contract, ConsultationNotificationService $notificationService)
    {
        $this->defaultScopes = ['mineAsPatient' => true];
        $this->relations = ['patient', 'doctorScheduleDayShift.day', 'doctor.rates', 'medicalSpeciality', 'replies'];
        parent::__construct($contract, ConsultationResource::class);
        $this->notificationService = $notificationService;
    }

    /**
     * Store a newly created resource in storage.
     * @param ConsultationRequest $request
     * @return JsonResponse
     */
    public function store(ConsultationRequest $request): JsonResponse
    {
        try {
            $consultation = $this->contract->create($request->validated());
            $this->relations[] = 'attachments';
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
   /**
    * Display the specified resource.
    * @param Consultation $consultation
    * @return JsonResponse
    */
   public function show(Consultation $consultation): JsonResponse
   {
       try {
           if (!$consultation->isMineAsPatient())
               abort(422, __('messages.not_allowed'));
           $this->relations = array_merge($this->relations, ['attachments', 'vendors', 'patient.diseases']);
           return $this->respondWithModel($consultation);
       }catch (Exception $e) {
           return $this->respondWithError($e->getMessage());
       }
   }
    /**
     * Update the specified resource in storage.
     *
     * @param ConsultationRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function update(ConsultationRequest $request, Consultation $consultation) : JsonResponse
    {
        try {
            $consultation = $this->contract->update($consultation, $request->validated());
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function destroy(Consultation $consultation): JsonResponse
    {
        try {
            $this->contract->remove($consultation);
            return $this->respondWithSuccess(__('messages.deleted'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * active & inactive the specified resource from storage.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function changeActivation(Consultation $consultation): JsonResponse
    {
        try {
            $this->contract->toggleField($consultation, 'is_active');
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Cancel the specified resource from storage.
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function cancel(Consultation $consultation): JsonResponse
    {
        if (!$consultation->patientCanCancel())
            abort(422, __('messages.patient_can_not_cancel'));
        try {
            $consultation = $this->contract->update($consultation, ['status' => ConsultationStatusConstants::PATIENT_CANCELLED->value]);
            $this->notificationService->patientCancel($consultation);
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Confirm referral
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function confirmReferral(Consultation $consultation): JsonResponse
    {
        if (!$consultation->patientCanConfirmReferral())
            abort(422, __('messages.patient_can_not_confirm_referral'));
        try {
            $consultation = $this->contract->update($consultation, ['status' => ConsultationStatusConstants::PATIENT_CONFIRM_REFERRAL->value]);
            $this->notificationService->patientCancel($consultation);
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * get urgent doctor replies
     *
     * @return JsonResponse
     */
    public function replies(): JsonResponse
    {
        request()->merge(['scope' => 'full']);
        try {
            $filters = [
                'urgentWithNoDoctor' => true,
                'medicalSpeciality' => request('medicalSpeciality'),
                'patient' => request('patient') ?? auth()->user()->patient?->id
            ];
            $consultation = $this->contract->findByFilters($filters, ['replies.rates', 'patient', 'medicalSpeciality'], false);
            if (!$consultation)
                return $this->respondWithSuccess(__('messages.no_data'));
            if (request('orderBy') == 'topRated'){
                $consultation->replies = $consultation->replies->sortByDesc(function ($reply) {
                    return $reply->rates->avg('value');
                });
            }elseif (request('orderBy') == 'highestPrice'){
                $consultation->replies = $consultation->replies->sortBy('amount')->reverse();
            }elseif (request('orderBy') == 'lowestPrice'){
                $consultation->replies = $consultation->replies->sortBy('amount');
            }
            $this->relations = ['replies.rates', 'medicalSpeciality'];
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * approve urgent doctor offer
     * @param PatientUrgentApproveRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function approveUrgentDoctorOffer(PatientUrgentApproveRequest $request, Consultation $consultation)
    {
        try {
            $data = $request->validated();
            $consultation = $this->contract->update($consultation, ['doctor_id' => $data['doctor_id'],
                'amount' => $data['amount'], 'status' => ConsultationStatusConstants::URGENT_PATIENT_APPROVE_DOCTOR_OFFER->value, 'is_active' => false]);
            $this->contract->syncWithoutDetaching($consultation, 'replies', $data['replies']);
            $this->notificationService->patientAcceptDoctorOffer($consultation);
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * reject urgent doctor offer
     * @param PatientUrgentRejectRequest $request
     * @param Consultation $consultation
     * @return JsonResponse
     */
    public function rejectUrgentDoctorOffer(PatientUrgentRejectRequest $request, Consultation $consultation)
    {
        try {
            $data = $request->validated();
            $doctor = resolve(DoctorContract::class)->find($data['doctor_id']);
            $this->contract->syncWithoutDetaching($consultation, 'replies', $data['replies']);
            $this->notificationService->patientRejectDoctorOffer($consultation, $doctor);
            return $this->respondWithModel($consultation);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function referralVendors(): JsonResponse
    {
        try {
            $consultations = $this->contract->findAllByFilters(['allReferrals' => true, 'patient' => auth()->user()->patient?->id], ['vendors', 'doctor'], false);
            
            $vendors = [];

            foreach ($consultations as $consultation) {
                foreach ($consultation->vendors as $vendor) {
                    $vendors[] = $this->referralResponse($consultation, $vendor);
                }
            }
            return response()->json(['status' => 200, 'data' => $vendors]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function otherReferralVendors(): JsonResponse
    {
        try {
            $consultations = $this->contract->findAllByFilters(['otherReferrals' => true, 'patient' => auth()->user()->patient?->id], ['vendors'], false);
            
            $vendors = [];

            foreach ($consultations as $consultation) {
                foreach ($consultation->vendors as $vendor) {
                    $vendors[] = $this->referralResponse($consultation, $vendor);
                }
            }
            return response()->json(['status' => 200, 'data' => $vendors]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function testReferralVendors(): JsonResponse
    {
        try {
            $consultations = $this->contract->findAllByFilters(['testReferrals' => true, 'patient' => auth()->user()->patient?->id], ['vendors', 'doctor'], false);
            
            $vendors = [];

            foreach ($consultations as $consultation) {
                foreach ($consultation->vendors as $vendor) {
                    $vendors[] = $this->referralResponse($consultation, $vendor);
                }
            }
            return response()->json(['status' => 200, 'data' => $vendors]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function raysReferralVendors(): JsonResponse
    {
        try {
            $consultations = $this->contract->findAllByFilters(['raysReferrals' => true, 'patient' => auth()->user()->patient?->id], ['vendors', 'doctor'], false);
            
            $vendors = [];

            foreach ($consultations as $consultation) {
                foreach ($consultation->vendors as $vendor) {
                    $vendors[] = $this->referralResponse($consultation, $vendor);
                }
            }
            return response()->json(['status' => 200, 'data' => $vendors]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function referralsByType()
    {
        try {
            $filters = ['otherReferrals' => true];

            if (request('type') == 'test') {
                $filters = ['testReferrals' => true];
            } elseif (request('type') == 'rays') {
                $filters = ['raysReferrals' => true];
            }

            $consultations = $this->contract->findAllByFilters(['patient' => auth()->user()->patient?->id] + $filters, ['vendors', 'doctor'], false);
            
            $vendors = [];

            foreach ($consultations as $consultation) {
                foreach ($consultation->vendors as $vendor) {
                    $vendors[] = $this->referralResponse($consultation, $vendor);
                }
            }
            return response()->json(['status' => 200, 'data' => $vendors]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function referralResponse($consultation, $vendor)
    {
        return [
            'id'              => $vendor->pivot->id,
            'type'            => $vendor->pivot->type,
            'transfer_reason' => $vendor->pivot->transfer_reason,
            'transfer_notes'  => $vendor->pivot->transfer_notes,
            'attachments'     => $vendor->pivot->attachments,

            'consultation' => [
                'id'              => $consultation->id,
                'transfer_reason' => $vendor->pivot->transfer_reason,
                'transfer_notes'  => $vendor->pivot->transfer_notes,
            ],

            'vendor' => [
                'id'      => $vendor->id,
                'name'    => $vendor->user->name,
                'address' => $vendor->user->address,
                'phone'   => $vendor->user->phone,
                'avatar'  => new FileResource($vendor->user->avatar) 
            ],

            'doctor' => [
                'id'                     => $consultation->doctor->id,
                'name'                   => $consultation->doctor->user->name,
                'avatar'                 => new FileResource($consultation->doctor->user->avatar),
                'doctorScheduleDayShift' => new DoctorScheduleDayShiftResource($consultation->doctorScheduleDayShift),
            ],
        ];
    }

    public function addFilesToReferral(ConsultationVendorFileRequest $request, $referral_id)
    {
        $referral = ConsultationVendor::find($referral_id);
        $referral->attachments()->sync($request->attachments);
        return $this->respondWithSuccess(__('messages.updated'));
    }
}
