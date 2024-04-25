<?php

namespace App\Models;

use App\Constants\CouponTypeConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Coupon extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['code', 'description', 'discount_type', 'discount_amount', 'valid_from', 'valid_to', 'user_limit', 'total_limit', 'is_active'];
    protected array $filters = ['keyword', 'active'];
    protected array $searchable = [];
    protected array $dates = ['valid_from', 'valid_to'];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];
    protected $casts = [
        'discount_type' => CouponTypeConstants::class
    ];

    //---------------------relations-------------------------------------
    public function medicalSpecialities(): BelongsToMany
    {
        return $this->belongsToMany(MedicalSpeciality::class, 'coupon_medical_speciality');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'coupon_id');
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------

    //---------------------Scopes-------------------------------------

}
