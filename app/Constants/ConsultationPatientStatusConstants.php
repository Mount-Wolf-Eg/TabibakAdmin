<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationPatientStatusConstants : int
{
    use ConstantsTrait;

    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;

    public function getLabels($value):string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::APPROVED => __('messages.approved'),
            self::REJECTED => __('messages.rejected'),
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
