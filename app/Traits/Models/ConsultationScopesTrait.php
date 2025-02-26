<?php

namespace App\Traits\Models;

use App\Constants\ConsultationPatientStatusConstants;
use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTypeConstants;
use App\Constants\ConsultationVendorStatusConstants;
use App\Constants\ConsultationVendorTypeConstants;
use App\Models\Consultation;

trait ConsultationScopesTrait
{
    //---------------------Scopes-------------------------------------

    public function scopeOfMineAsPatient($query)
    {
        $relatives = auth()->user()->patient?->relatives->pluck('id');
        $all = $relatives->push(auth()->user()->patient?->id);
        return $query->whereIn('patient_id', $all)
            ->whereNotNull('patient_id');
    }

    public function scopeOfDoctorsList($query)
    {
        return $query->where(function ($q) {
            $q->where('doctor_id', auth()->user()->doctor?->id)->whereNotNull('doctor_id');
            $q->orWhere(function ($q) {
                $q->where('type', ConsultationTypeConstants::URGENT)
                    ->whereIn('status', [
                        ConsultationStatusConstants::PENDING,
                        ConsultationStatusConstants::URGENT_HAS_DOCTORS_REPLIES
                    ])
                    ->whereIn('medical_speciality_id', auth()->user()->doctor?->medicalSpecialities->pluck('id'))
                    ->whereNull('doctor_id');
            });
        });
    }

    public function scopeOfMineAsDoctor($query)
    {
        return $query->ofDoctor(auth()->user()->doctor?->id)
            ->whereNotNull('doctor_id');
    }

    public function scopeOfDoctor($query)
    {
        return $query->where('doctor_id', auth()->user()->doctor?->id);
    }

    public function scopeOfMineAsVendor($query)
    {
        return $query->whereHas('vendors', function ($q) {
            $q->where('vendor_id', auth()->user()->vendor?->id);
        });
    }

    public function scopeOfVendorAcceptedStatus($query)
    {
        return $query->whereHas('vendors', function ($q) {
            $q->where('status', ConsultationVendorStatusConstants::ACCEPTED->value);
        });
    }

    public function scopeOfVendorRejectedStatus($query)
    {
        return $query->whereHas('vendors', function ($q) {
            $q->where('status', ConsultationVendorStatusConstants::REJECTED->value);
        });
    }

    public function scopeOfType($query, $type)
    {
        return $query->whereIn('type', (array)$type);
    }

    public function scopeOfCreationDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function scopeOfMyVendorStatus($query, $status)
    {
        $vendorId = auth()->user()->vendor?->id;
        return $query->whereHas('vendors', function ($q) use ($vendorId, $status) {
            $q->where('vendor_id', $vendorId)->where('status', $status);
        });
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->whereIn('status', (array)$status);
    }

    public function scopeOfCompleted($query, $value = "true")
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        $completedStatuses = [
            ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT->value,
            ConsultationStatusConstants::PATIENT_CANCELLED->value,
            ConsultationStatusConstants::DOCTOR_CANCELLED->value,
            ConsultationStatusConstants::REFERRED_TO_ANOTHER_DOCTOR->value,
            ConsultationStatusConstants::REFERRED_FROM_ANOTHER_DOCTOR->value
        ];
        if ($value) {
            return $query->ofStatus($completedStatuses);
        }
        return $query->whereNotIn('status', $completedStatuses);
    }

    public function scopeOfUrgentWithNoDoctor($query)
    {
        return $query->where('type', ConsultationTypeConstants::URGENT)
            ->whereHas('replies', fn($q) => $q->where('status', '!=', ConsultationPatientStatusConstants::APPROVED->value))
            ->whereNull('doctor_id');
    }

    public function scopeOfMedicalSpeciality($query, $medicalSpeciality)
    {
        return $query->where('medical_speciality_id', $medicalSpeciality);
    }

    public function scopeOfPatient($query, $patientId)
    {
        return $query->whereIn('patient_id', (array)$patientId);
    }

    public function scopeOfCreatedBeforeHour($query)
    {
        return $query->whereTime('created_at', '<', now()->subHour());
    }

    public function scopeOfDayShift($query, $dayShiftId)
    {
        return $query->where('doctor_schedule_day_shift_id', $dayShiftId);
    }

    public function scopeOfOnlyApprovedReferral($query)
    {
        return $query->where('status', '!=',  ConsultationStatusConstants::REFERRED_FROM_ANOTHER_DOCTOR->value);
    }

    public function scopeOfِActive($query)
    {
        return $query->whereIn('is_active', true);
    }

    public function scopeOfDoctorNoConsultationPatient($query)
    {
        return $query->where('patient_id', '!=', auth()->user()->patient?->id);
    }

    public function scopeOfAllReferrals($query)
    {
        return $query->where(function ($query) {
            $query->where('type', ConsultationTypeConstants::REFERRAL)
                ->orWhereHas('vendors');
        });
    }

    public function scopeOfOtherReferrals($query)
    {
        return $query->whereHas('vendors', function ($query) {
            $query->where('type', ConsultationVendorTypeConstants::OTHER);
        });
    }

    public function scopeOfRaysReferrals($query)
    {
        return $query->whereHas('vendors', function ($query) {
            $query->where('type', ConsultationVendorTypeConstants::RAYS);
        });
    }

    public function scopeOfTestReferrals($query)
    {
        return $query->whereHas('vendors', function ($query) {
            $query->where('type', ConsultationVendorTypeConstants::TEST);
        });
    }
    //---------------------Scopes-------------------------------------

}
