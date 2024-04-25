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
    protected $fillable = ['user_id', 'payable_id', 'payable_type', 'transaction_id',
        'amount', 'currency_id', 'payment_method', 'status', 'metadata'];
    protected array $filters = ['keyword', 'status', 'paymentMethod', 'creationDate', 'user',
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
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
