<?php

namespace App\Models;

use App\Constants\ConsultationContactTypeConstants;
use App\Constants\ConsultationPaymentTypeConstants;
use App\Constants\ConsultationTransferCaseRateConstants;
use App\Constants\ConsultationVendorStatusConstants;
use App\Constants\ConsultationVendorTypeConstants;
use App\Constants\FileConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class ConsultationVendor extends Model
{
    use ModelTrait, SearchTrait, HasTranslations;

    public $table = 'consultation_vendor';

    public const ADDITIONAL_PERMISSIONS = [];

    protected $fillable = ['consultation_id', 'vendor_id', 'status', 'type','transfer_reason', 'transfer_notes', 'transfer_case_rate'];
    
    protected array $filters = [];
    
    protected array $searchable = ['id'];
    protected array $dates = [];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];
    
    protected $casts = [
        'status' => ConsultationVendorStatusConstants::class,
        'type' => ConsultationVendorTypeConstants::class,
        'contact_type' => ConsultationContactTypeConstants::class,
        'payment_type' => ConsultationPaymentTypeConstants::class,
        'transfer_case_rate' => ConsultationTransferCaseRateConstants::class
    ];

    //---------------------relations-------------------------------------
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->where('type', FileConstants::FILE_CONSULTATION_REFERRAL);
    }
    //---------------------relations-------------------------------------

    //---------------------constants-------------------------------------
    
    //---------------------constants-------------------------------------

    //---------------------methods-------------------------------------
    
    //---------------------methods-------------------------------------

    //---------------------attributes-------------------------------------
    
    //---------------------attributes-------------------------------------
}
