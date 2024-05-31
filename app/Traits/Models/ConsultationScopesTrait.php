<?php

namespace App\Traits\Models;

use App\Constants\ConsultationPatientStatusConstants;
use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTypeConstants;
use App\Constants\ConsultationVendorStatusConstants;

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
                    ->whereIn('status', [ConsultationStatusConstants::PENDING,
                        ConsultationStatusConstants::URGENT_HAS_DOCTORS_REPLIES])
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
        return $query->where('type', $type);
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
        if ($value) {
            return $query->ofStatus([ConsultationStatusConstants::PATIENT_CANCELLED->value,
                ConsultationStatusConstants::DOCTOR_CANCELLED->value,
                ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT->value]);
        }
        return $query->ofStatus(ConsultationStatusConstants::PENDING->value)
            ->ofType(ConsultationTypeConstants::WITH_APPOINTMENT);
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
        return $query->where('patient_id', $patientId);
    }
    //---------------------Scopes-------------------------------------

}
