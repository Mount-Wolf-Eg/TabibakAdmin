<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum VendorTypeConstants : string
{
    use ConstantsTrait;

    case HOSPITAL = 'Hospital';
    case CLINIC = 'Clinic';
    case PHARMACY = 'Pharmacy';
    case HOMECARE = 'Home Care';
    case LAB = 'Lab';
}
