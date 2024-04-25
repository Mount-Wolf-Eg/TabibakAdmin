<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum PaymentMethodConstants : int
{
    use ConstantsTrait;

    case CREDIT_CARD = 1;
    case DEBIT_CARD = 2;

    public function getLabels($value):string
    {
        return match ($value) {
            self::CREDIT_CARD => __('messages.credit_card'),
            self::DEBIT_CARD => __('messages.debit_card')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
