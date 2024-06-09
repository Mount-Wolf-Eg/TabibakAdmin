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
    case PATIENT_CANCELLED = 5;
    case DOCTOR_CANCELLED = 6;
    case REFERRED_TO_ANOTHER_DOCTOR  = 7;
    case REFERRED_FROM_ANOTHER_DOCTOR  = 8;
    case PATIENT_CONFIRM_REFERRAL = 9;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::URGENT_HAS_DOCTORS_REPLIES => __('messages.urgent_has_doctors_replies'),
            self::URGENT_PATIENT_APPROVE_DOCTOR_OFFER => __('messages.urgent_patient_approve_doctor_offer'),
            self::DOCTOR_APPROVED_MEDICAL_REPORT => __('messages.doctor_approved_medical_report'),
            self::PATIENT_CANCELLED, self::DOCTOR_CANCELLED => __('messages.cancelled'),
            self::REFERRED_TO_ANOTHER_DOCTOR, self::REFERRED_FROM_ANOTHER_DOCTOR => __('messages.referred_to_another_doctor'),
            self::PATIENT_CONFIRM_REFERRAL => __('messages.patient_confirm_referral'),
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
