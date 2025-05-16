<?php

namespace App\Services\Repositories;

use App\Models\GeneralSettings;

class PaymentCalculator
{
    public function calc(float $amount): array
    {
        $appPercentage = GeneralSettings::getSettingValue('app_payment_percentage') / 100;
        $taxPercentage = 0;

        if (auth()->check() && auth()->user()->patient && substr(auth()->user()->patient->national_id, 0, 1) == '1') {
            $appPercentage = GeneralSettings::getSettingValue('app_payment_percentage') / 100;
        }

        $appAmount     = round($amount * $appPercentage, 2);
        $doctorAmount  = round($amount - $appAmount, 2);
        $taxAmount     = round($amount * $taxPercentage, 4);
        $totalAmount   = round($amount + $taxAmount, 2);

        return [
            'app_amount'    => $appAmount,
            'doctor_amount' => $doctorAmount,
            'tax_amount'    => $taxAmount,
            'total_amount'  => $totalAmount,
        ];
    }
}
