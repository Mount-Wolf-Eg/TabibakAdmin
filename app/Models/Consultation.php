<?php

namespace App\Models;

use App\Constants\ConsultationContactTypeConstants;
use App\Constants\ConsultationPaymentTypeConstants;
use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTransferCaseRateConstants;
use App\Constants\ConsultationTypeConstants;
use App\Constants\ConsultationVendorStatusConstants;
use App\Constants\FileConstants;
use App\Constants\ReminderConstants;
use App\Constants\ConsultationPatientStatusConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Consultation extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, SoftDeletes, HasTranslations;

    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['doctor_id', 'patient_id', 'status', 'medical_speciality_id',
        'patient_description', 'doctor_description', 'medical_review', 'prescription', 'type',
        'doctor_schedule_day_shift_id', 'contact_type', 'reminder_at', 'transfer_reason',
        'transfer_notes', 'transfer_case_rate', 'payment_type', 'amount',
        'coupon_id', 'is_active'];
    protected array $filters = ['keyword', 'mineAsPatient', 'active', 'mineAsDoctor',
        'mineAsVendor', 'vendorAcceptedStatus', 'vendorRejectedStatus', 'type', 'doctor',
        'myVendorStatus', 'creationDate', 'status', 'completed', 'urgentWithNoDoctor'];
    protected array $searchable = ['patient.user.name', 'doctor.user.name', 'id'];
    protected array $dates = ['reminder_at'];
    public array $filterModels = [];
    public array $filterCustom = ['types', 'paymentMethods', 'reminders', 'transferCaseRates',
        'statuses', 'contactTypes'];
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

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
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
            ->withPivot('doctor_set_consultation_at', 'amount', 'status')->withTimestamps();
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------

    public function scopeOfMineAsPatient($query)
    {
        return $query->where('patient_id', auth()->user()->patient?->id)->whereNotNull('patient_id');
    }

    public function scopeOfMineAsDoctor($query)
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

    public function scopeOfDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
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
            return $query->ofStatus([ConsultationStatusConstants::CANCELLED->value,
                ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT->value]);
        }
        return $query->ofStatus(ConsultationStatusConstants::PENDING->value);
    }

    public function scopeOfUrgentWithNoDoctor($query)
    {
        return $query->where('type', ConsultationTypeConstants::URGENT)
            ->where(function ($q) {
                $q->whereHas('replies', fn($q) => $q->where('status', '!=', ConsultationPatientStatusConstants::APPROVED->value))
                    ->orWhereDoesntHave('replies');
            })
            ->whereNull('doctor_id');
    }
    //---------------------Scopes-------------------------------------

    //---------------------constants-------------------------------------
    public static function types(): array
    {
        return ConsultationTypeConstants::valuesCollection();
    }

    public static function paymentMethods(): array
    {
        return ConsultationPaymentTypeConstants::valuesCollection();
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
    //---------------------constants-------------------------------------

    //---------------------methods-------------------------------------
    public function isMineAsPatient(): bool
    {
        return $this->patient_id == auth()->user()->patient?->id;
    }

    public function isMineAsDoctor(): bool
    {
        return $this->doctor_id == auth()->user()->doctor?->id;
    }

    public function isMineAsVendor()
    {
        return $this->vendors->contains('id', auth()->user()->vendor?->id);
    }

    public function isPendingVendor($vendorId)
    {
        return $this->vendors->where('id', $vendorId)
            ->where('pivot.status', ConsultationVendorStatusConstants::PENDING->value)->isNotEmpty();
    }

    public function doctorCanCancel(): bool
    {
        return $this->isMineAsDoctor() && $this->status->is(ConsultationStatusConstants::PENDING);
    }

    public function doctorCanDoReferral(): bool
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
        return $this->doctorCanDoReferral();
    }

    public function doctorCanApproveMedicalReport(): bool
    {
        return $this->doctorCanDoReferral();
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
    //---------------------methods-------------------------------------

    //---------------------attributes-------------------------------------
    //---------------------attributes-------------------------------------
}
