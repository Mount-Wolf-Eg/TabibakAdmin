<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationTransferCaseRateConstants : int
{
    use ConstantsTrait;

    case NOT_URGENT = 1;
    case NORMAL = 2;
    case URGENT = 3;

    public static function getLabels($value): string
    {
        return match ($value) {
            self::NOT_URGENT => __('messages.not_urgent'),
            self::NORMAL => __('messages.normal'),
            self::URGENT => __('messages.urgent')
        };
    }

    public static function getLabelsByValue($value):string
    {
        return match ($value) {
            self::NOT_URGENT->value => __('messages.not_urgent'),
            self::NORMAL->value => __('messages.normal'),
            self::URGENT->value => __('messages.urgent')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
