<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum UserGenderConstants : int
{
    use ConstantsTrait;

    case MALE = 1;
    case FEMALE = 2;

    public function getLabels($value):string
    {
        return match ($value) {
            self::MALE => __('messages.male'),
            self::FEMALE => __('messages.female'),
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }
}
