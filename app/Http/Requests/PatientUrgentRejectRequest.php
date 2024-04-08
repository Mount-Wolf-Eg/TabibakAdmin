<?php

namespace App\Http\Requests;

use App\Constants\ConsultationPatientStatusConstants;
use Illuminate\Foundation\Http\FormRequest;

class PatientUrgentRejectRequest extends PatientUrgentStatusRequest
{
    public function __construct()
    {
        parent::__construct(ConsultationPatientStatusConstants::REJECTED->value);
    }
}

