<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum PaymentStatusConstants : int
{
    use ConstantsTrait;

    case PENDING = 1;
    case COMPLETED = 2;
    case FAILED = 3;
    case CANCELLED = 4;
    case REFUNDED = 5;

    public function getLabels($value):string
    {
        return match ($value) {
            self::PENDING => __('messages.pending'),
            self::COMPLETED => __('messages.completed'),
            self::FAILED => __('messages.failed'),
            self::CANCELLED => __('messages.cancelled'),
            self::REFUNDED => __('messages.refunded')
        };
    }

    public function label(): string
    {
        return self::getLabels($this);
    }

    public function getColorClasses($value):string
    {
        return match ($value) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::CANCELLED => 'secondary',
            self::REFUNDED => 'info'
        };
    }

    public function colorClass(): string
    {
        return self::getColorClasses($this);
    }
}
