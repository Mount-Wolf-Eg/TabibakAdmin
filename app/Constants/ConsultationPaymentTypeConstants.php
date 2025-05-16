<?php

namespace App\Constants;

use App\Traits\ConstantsTrait;

enum ConsultationPaymentTypeConstants: int
{
    use ConstantsTrait;

    case CREDIT = 1;
    case WALLET = 2;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::CREDIT => __('messages.credit'),
            self::WALLET => __('messages.wallet')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
