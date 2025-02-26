<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ConsultationVendorTypeConstants : Int
{
    use ConstantsTrait;

    case OTHER = 0;
    case RAYS = 1;
    case TEST = 2;

    public static function getLabels($value):string
    {
        return match ($value) {
            self::OTHER => __('messages.other'),
            self::RAYS => __('messages.rays'),
            self::TEST => __('messages.test'),
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
