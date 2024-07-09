<?php

namespace App\Models;

use App\Constants\CouponTypeConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class Coupon extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;

    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['code', 'description', 'discount_type', 'discount_amount',
        'valid_from', 'valid_to', 'user_limit', 'total_limit', 'is_active'];
    protected array $filters = ['keyword', 'active', 'valid', 'used', 'expired'];
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfValid($query)
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->where('total_limit', '>', function ($query) {
                $query->selectRaw('count(*)')
                    ->from('payments')
                    ->whereColumn('coupon_id', 'coupons.id');
            });
    }

    public function scopeOfUsed($query)
    {
        return $query->whereHas('payments', function ($query) {
            $query->where('payer_id', auth()->id());
        });
    }

    public function scopeOfExpired($query)
    {
        return $query->where('valid_to', '<', now())
            ->orWhere('total_limit', '<=', function ($query) {
                $query->selectRaw('count(*)')
                    ->from('payments')
                    ->whereColumn('coupon_id', 'coupons.id');
            });
    }
    //---------------------Scopes-------------------------------------

    //---------------------Attributes-------------------------------------
    public function status(): Attribute
    {
        return Attribute::make(function () {
            return $this->isValid() ? __('messages.valid') : __('messages.expired');
        });
    }

    public function discountAmountTxt(): Attribute
    {
        return Attribute::make(function () {
            return $this->discount_type == CouponTypeConstants::PERCENTAGE->value
                ? $this->discount_amount . '%'
                : $this->discount_amount . ' SAR';
        });
    }

    //---------------------Attributes-------------------------------------

    public function isValid(): bool
    {
        return $this->is_active
            && $this->valid_from->isPast() && $this->valid_to->isFuture()
            && $this->payments->count() < $this->total_limit;
    }

    public function isValidForUser($userId, $specialityId = null): bool
    {
        return $this->isValid()
            && $this->payments->where('payer_id', $userId)->count() < $this->user_limit
            && $this->medicalSpecialities->contains($specialityId);
    }

    public function applyDiscount($amount): float
    {
        if ($this->discount_type == CouponTypeConstants::PERCENTAGE) {
            return $amount - ($amount * $this->discount_amount / 100);
        }
        return $amount < $this->discount_amount ? 0 : $amount - $this->discount_amount;
    }

}
