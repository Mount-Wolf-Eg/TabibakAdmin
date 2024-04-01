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
        'other_diseases' , 'latest_surgeries', 'doctor_schedule_day_shift_id', 'contact_type',
        'reminder_at', 'transfer_reason', 'transfer_notes', 'transfer_case_rate',
        'payment_type', 'amount', 'coupon_id', 'is_active'];
    protected array $filters = ['keyword', 'mineAsPatient', 'active', 'mineAsDoctor',
        'mineAsVendor', 'vendorAcceptedStatus', 'vendorRejectedStatus', 'type', 'doctor',
        'myVendorStatus', 'creationDate'];
    protected array $searchable = ['patient.user.name', 'doctor.user.name', 'id'];
    protected array $dates = ['reminder_at'];
    public array $filterModels = [];
    public array $filterCustom = ['types', 'paymentMethods', 'reminders'];
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

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'consultation_disease')->withTimestamps();
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------

    public function scopeOfMineAsPatient($query)
    {
        return $query->where('patient_id', auth()->user()->patient?->id)->whereNotNull('patient_id');
    }

    public function scopeOfMineAsDoctor($query)
    {
        return $query->where('doctor_id', auth()->user()->doctor?->id)->whereNotNull('doctor_id');
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
    //---------------------Scopes-------------------------------------

    //---------------------constants-------------------------------------
    public static function types(): array
    {
        return ConsultationTypeConstants::valuesCollection();
    }

    public static function  paymentMethods(): array
    {
        return ConsultationPaymentTypeConstants::valuesCollection();
    }

    public static function reminders(): array
    {
        return ReminderConstants::valuesCollection();
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

    public function getVendorStatusColor($vendorId): string
    {
        $vendor = $this->vendors->where('id', $vendorId)->first();
        if ($vendor) {
            $case =  ConsultationVendorStatusConstants::tryFrom($vendor->pivot->status);
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
            $case =  ConsultationVendorStatusConstants::tryFrom($vendor->pivot->status);
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
