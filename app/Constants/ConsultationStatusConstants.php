<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationStatusConstants : int
{
    use ConstantsTrait;

    case PENDING = 1;
    case DOCTOR_APPROVED_MEDICAL_REPORT = 2;
    case CANCELLED = 3;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::DOCTOR_APPROVED_MEDICAL_REPORT => __('messages.doctor_approved_medical_report'),
            self::CANCELLED => __('messages.cancelled')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
