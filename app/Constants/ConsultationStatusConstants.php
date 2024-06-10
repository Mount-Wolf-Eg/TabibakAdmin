<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationStatusConstants : int
{
    use ConstantsTrait;

    case PENDING = 1;
    case NEEDS_RESCHEDULE = 2;
    case URGENT_HAS_DOCTORS_REPLIES = 3;
    case URGENT_PATIENT_APPROVE_DOCTOR_OFFER = 4;
    case DOCTOR_APPROVED_MEDICAL_REPORT = 5;
    case PATIENT_CANCELLED = 6;
    case DOCTOR_CANCELLED = 7;
    case REFERRED_TO_ANOTHER_DOCTOR  = 8;
    case REFERRED_FROM_ANOTHER_DOCTOR  = 9;
    case PATIENT_CONFIRM_REFERRAL = 10;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::NEEDS_RESCHEDULE => __('messages.needs_reschedule'),
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
