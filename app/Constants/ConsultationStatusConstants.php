<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationStatusConstants : int
{
    use ConstantsTrait;

    case PENDING = 1;
    case DOCTOR_ACCEPTED_URGENT_CASE = 2;
    case DOCTOR_APPROVED_MEDICAL_REPORT = 3;
    case CANCELLED = 4;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::DOCTOR_ACCEPTED_URGENT_CASE => __('messages.doctor_accepted_urgent_case'),
            self::DOCTOR_APPROVED_MEDICAL_REPORT => __('messages.doctor_approved_medical_report'),
            self::CANCELLED => __('messages.cancelled')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
