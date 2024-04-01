<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum ReminderConstants : int
{
    use ConstantsTrait;

    case TEN_MINUTES = 10;
    case FIFTEEN_MINUTES = 15;
    case THIRTY_MINUTES = 30;
    case FORTY_FIVE_MINUTES = 45;
    case ONE_HOUR = 60;

    public static function getLabels($value): ?string
    {
        return match ($value) {
            self::TEN_MINUTES => __('messages.10_minutes'),
            self::FIFTEEN_MINUTES => __('messages.15_minutes'),
            self::THIRTY_MINUTES => __('messages.30_minutes'),
            self::FORTY_FIVE_MINUTES => __('messages.45_minutes'),
            self::ONE_HOUR => __('messages.1_hour')
        };
    }

    public function label(): ?string
    {
        return self::getLabels($this);
    }
}
