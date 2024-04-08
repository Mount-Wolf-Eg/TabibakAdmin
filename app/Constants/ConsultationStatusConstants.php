<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationStatusConstants : int
{
    use ConstantsTrait;

    case PENDING = 1;
    case URGENT_HAS_DOCTORS_REPLIES = 2;
    case URGENT_PATIENT_APPROVE_DOCTOR_OFFER = 3;
    case DOCTOR_APPROVED_MEDICAL_REPORT = 4;
    case CANCELLED = 5;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::URGENT_HAS_DOCTORS_REPLIES => __('messages.urgent_has_doctors_replies'),
            self::URGENT_PATIENT_APPROVE_DOCTOR_OFFER => __('messages.urgent_patient_approve_doctor_offer'),
            self::DOCTOR_APPROVED_MEDICAL_REPORT => __('messages.doctor_approved_medical_report'),
            self::CANCELLED => __('messages.cancelled')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
