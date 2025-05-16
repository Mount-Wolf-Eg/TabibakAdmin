<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum PaymentTypeConstants : int
{
    use ConstantsTrait;

    case WITHDRAWAL = 1;
    case DEPOSIT = 2;
    case REFUND = 3;

    public static function getLabels($value):string
    {
        return match ($value) {
            self::WITHDRAWAL => __('messages.withdrawal'),
            self::DEPOSIT => __('messages.deposit'),
            self::REFUND => __('messages.refund')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
