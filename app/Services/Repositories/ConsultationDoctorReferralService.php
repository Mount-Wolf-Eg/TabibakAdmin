<?php

namespace App\Services\Repositories;

use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTypeConstants;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;

class ConsultationDoctorReferralService
{
    private ConsultationContract $contract;
    public function __construct(ConsultationContract $contract)
    {
        $this->contract = $contract;
    }

    public function save(Consultation $consultation, $data): Consultation
    {
        $consultation->doctor_schedule_day_shift_id = $data['doctor_schedule_day_shift_id'];
        $consultation->doctor_id = $data['doctor_id'];
        $consultation->type = ConsultationTypeConstants::REFERRAL->value;
        $referralData = $consultation->toArray();
        $referralData['attachments'] = $consultation->attachments->pluck('id')->toArray();
        $referralData['vendors'] = $consultation->vendors->pluck('id')->toArray();
        $referralConsultation = $this->contract->create($referralData);
        $this->contract->update($consultation, [
            'parent_id' => $referralConsultation->id,
            'status' => ConsultationStatusConstants::REFERRED_TO_ANOTHER_DOCTOR->value,
        ]);
        return $referralConsultation;
    }

}
