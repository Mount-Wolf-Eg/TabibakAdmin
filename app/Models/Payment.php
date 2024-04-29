<?php

namespace App\Models;

use App\Constants\PaymentMethodConstants;
use App\Constants\PaymentStatusConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Payment extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['payer_id', 'beneficiary_id', 'payable_id', 'payable_type', 'coupon_id',
        'transaction_id', 'amount', 'currency_id', 'payment_method', 'status', 'metadata'];
    protected array $filters = ['keyword', 'status', 'paymentMethod', 'creationDate', 'payer',
        'fromCreationDate', 'toCreationDate'];
    protected array $searchable = ['transaction_id', 'currency.name'];
    protected array $dates = [];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];
    public $casts = [
        'metadata' => 'array',
        'status' => PaymentStatusConstants::class,
        'payment_method' => PaymentMethodConstants::class
    ];

    //---------------------relations-------------------------------------
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfPayer($query, $payer_id)
    {
        return $query->where('payer_id', $payer_id);
    }

    public function scopeCreationDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOfPaymentMethod($query, $payment_method)
    {
        return $query->where('payment_method', $payment_method);
    }

    public function scopeOfFromCreationDate($query, $date)
    {
        return $query->whereDate('created_at', '>=', $date);
    }

    public function scopeOfToCreationDate($query, $date)
    {
        return $query->whereDate('created_at', '<=', $date);
    }
    //---------------------Scopes-------------------------------------

}
