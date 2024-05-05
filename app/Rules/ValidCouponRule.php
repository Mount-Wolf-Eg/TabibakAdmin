<?php

namespace App\Rules;

use App\Repositories\Contracts\CouponContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidCouponRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coupon = resolve(CouponContract::class)->findBy('code', $value, false);
        if (!$coupon?->isValidForUser(auth()->id(), request('medical_speciality_id'))) {
            $fail(__('messages.invalid_coupon'));
        }
    }
}
