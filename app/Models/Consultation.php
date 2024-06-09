<?php

namespace App\Models;

use App\Constants\ConsultationContactTypeConstants;
use App\Constants\ConsultationPaymentTypeConstants;
use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTransferCaseRateConstants;
use App\Constants\ConsultationTypeConstants;
use App\Constants\ConsultationVendorStatusConstants;
use App\Constants\FileConstants;
use App\Constants\PaymentMethodConstants;
use App\Constants\PaymentStatusConstants;
use App\Constants\ReminderConstants;
use App\Constants\ConsultationPatientStatusConstants;
use App\Traits\Models\ConsultationScopesTrait;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Consultation extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations, ConsultationScopesTrait;

    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['parent_id', 'doctor_id', 'patient_id', 'status', 'medical_speciality_id',
        'patient_description', 'doctor_description', 'medical_review', 'prescription', 'type',
        'doctor_schedule_day_shift_id', 'contact_type', 'reminder_at', 'transfer_reason',
        'transfer_notes', 'transfer_case_rate', 'payment_type', 'amount',
        'is_active'];
    protected array $filters = ['keyword', 'mineAsPatient', 'active', 'mineAsDoctor',
        'mineAsVendor', 'vendorAcceptedStatus', 'vendorRejectedStatus', 'type', 'doctor',
        'myVendorStatus', 'creationDate', 'status', 'completed', 'urgentWithNoDoctor',
        'doctorsList', 'medicalSpeciality', 'doctor', 'patient', 'createdBeforeHour'];
    protected array $searchable = ['patient.user.name', 'doctor.user.name', 'id'];
    protected array $dates = ['reminder_at'];
    public array $filterModels = [];
    public array $filterCustom = ['types', 'paymentMethods', 'reminders', 'transferCaseRates',
        'statuses', 'contactTypes', 'paymentStatuses', 'paymentTypes'];
    public array $translatable = [];
    protected $casts = [
        'status' => ConsultationStatusConstants::class,
        'type' => ConsultationTypeConstants::class,
        'contact_type' => ConsultationContactTypeConstants::class,
        'payment_type' => ConsultationPaymentTypeConstants::class,
        'transfer_case_rate' => ConsultationTransferCaseRateConstants::class,
        'prescription' => 'array'
    ];

    //---------------------relations-------------------------------------
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('type', FileConstants::FILE_TYPE_CONSULTATION_ATTACHMENTS);
    }

    public function medicalSpeciality(): BelongsTo
    {
        return $this->belongsTo(MedicalSpeciality::class);
    }

    public function doctorScheduleDayShift(): BelongsTo
    {
        return $this->belongsTo(DoctorScheduleDayShift::class);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'consultation_vendor')
            ->withPivot('status')->withTimestamps();
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function replies(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'consultation_doctor_replies')
            ->withPivot('doctor_set_consultation_at', 'amount', 'status', 'reason')->withTimestamps();
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }
    //---------------------relations-------------------------------------
    //---------------------constants-------------------------------------
    public static function types(): array
    {
        return ConsultationTypeConstants::valuesCollection();
    }

    public static function paymentTypes(): array
    {
        return ConsultationPaymentTypeConstants::valuesCollection();
    }

    public static function paymentMethods(): array
    {
        return PaymentMethodConstants::valuesCollection();
    }

    public static function reminders(): array
    {
        return ReminderConstants::valuesCollection();
    }

    public static function transferCaseRates(): array
    {
        return ConsultationTransferCaseRateConstants::valuesCollection();
    }

    public static function statuses(): array
    {
        return ConsultationStatusConstants::valuesCollection();
    }

    public static function contactTypes(): array
    {
        return ConsultationContactTypeConstants::valuesCollection();
    }

    public static function paymentStatuses(): array
    {
        return PaymentStatusConstants::valuesCollection();
    }
    //---------------------constants-------------------------------------

    //---------------------methods-------------------------------------
    public function isMineAsPatient(): bool
    {
        $validPatientIds = array_merge(
            auth()->user()->patient?->relatives->pluck('id')->toArray() ?? [],
            [auth()->user()->patient?->id]
        );
        return in_array($this->patient_id, $validPatientIds);
    }

    public function isMineAsDoctor(): bool
    {
        return $this->doctor_id == auth()->user()->doctor?->id;
    }

    public function isMineAsVendor(): bool
    {
        return $this->vendors->contains('id', auth()->user()->vendor?->id);
    }

    public function isPendingVendor($vendorId): bool
    {
        return $this->vendors->where('id', $vendorId)
            ->where('pivot.status', ConsultationVendorStatusConstants::PENDING->value)->isNotEmpty();
    }

    public function doctorCanCancel(): bool
    {
        return $this->isMineAsDoctor() && $this->status->is(ConsultationStatusConstants::URGENT_PATIENT_APPROVE_DOCTOR_OFFER);
    }

    public function doctorCanDoVendorReferral(): bool
    {
        if ($this->isMineAsDoctor()) {
            return !$this->status->is(ConsultationStatusConstants::REFERRED_TO_ANOTHER_DOCTOR);
        }
        return false;
    }

    public function doctorCanDoDoctorReferral(): bool
    {
        if ($this->isMineAsDoctor()) {
            if ($this->type->is(ConsultationTypeConstants::URGENT)) {
                return $this->status->is(ConsultationStatusConstants::URGENT_PATIENT_APPROVE_DOCTOR_OFFER);
            }
            return $this->status->is(ConsultationStatusConstants::PENDING);
        }
        return false;
    }

    public function doctorCanWritePrescription(): bool
    {
        return $this->doctorCanDoVendorReferral();
    }

    public function doctorCanApproveMedicalReport(): bool
    {
        return $this->doctorCanDoVendorReferral();
    }

    public function doctorCanAcceptUrgentCase(): bool
    {
        return ($this->status->is(ConsultationStatusConstants::PENDING)
            || $this->status->is(ConsultationStatusConstants::URGENT_HAS_DOCTORS_REPLIES));
    }

    public function patientCanChangeDoctorStatusOffer($doctorId): bool
    {
        return ($this->status->is(ConsultationStatusConstants::PENDING)
                || $this->status->is(ConsultationStatusConstants::URGENT_HAS_DOCTORS_REPLIES))
            && $this->replies->where('id', $doctorId)
                ->where('pivot.status', ConsultationPatientStatusConstants::PENDING->value)->isNotEmpty();
    }

    public function getVendorStatusColor($vendorId): string
    {
        $vendor = $this->vendors->where('id', $vendorId)->first();
        if ($vendor) {
            $case = ConsultationVendorStatusConstants::tryFrom($vendor->pivot->status);
            if ($case) {
                return $case->color();
            }
        }
        return '';
    }

    public function getVendorStatusTxt($vendorId): string
    {
        $vendor = $this->vendors->where('id', $vendorId)->first();
        if ($vendor) {
            $case = ConsultationVendorStatusConstants::tryFrom($vendor->pivot->status);
            if ($case) {
                return $case->label();
            }
        }
        return '';
    }

    public function isCancelled(): bool
    {
        return $this->status->is(ConsultationStatusConstants::PATIENT_CANCELLED)
            || $this->status->is(ConsultationStatusConstants::DOCTOR_CANCELLED);
    }

    public function patientCanCancel(): bool
    {
        return $this->status->is(ConsultationStatusConstants::PENDING)
            || $this->status->is(ConsultationStatusConstants::URGENT_HAS_DOCTORS_REPLIES)
            || $this->status->is(ConsultationStatusConstants::REFERRED_FROM_ANOTHER_DOCTOR);
    }

    public function patientCanConfirmReferral(): bool
    {
        return $this->status->is(ConsultationStatusConstants::REFERRED_FROM_ANOTHER_DOCTOR);
    }
    //---------------------methods-------------------------------------

    //---------------------attributes-------------------------------------
    //---------------------attributes-------------------------------------
}
