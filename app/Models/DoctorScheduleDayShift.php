<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class DoctorScheduleDayShift extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['doctor_schedule_day_id', 'parent_id', 'from_time', 'to_time', 'notes', 'is_active'];
    protected array $filters = ['keyword', 'availableSlots', 'active'];
    protected array $searchable = [];
    protected array $dates = ['from_time', 'to_time'];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];

    //---------------------relations-------------------------------------
    public function day(): BelongsTo
    {
        return $this->belongsTo(DoctorScheduleDay::class, 'doctor_schedule_day_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }

    public function availableSlots(): HasMany
    {
        return $this->slots()->whereDoesntHave('consultation');
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfAvailableSlots(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereDoesntHave('consultation')
                ->orWhereHas('consultation', function ($q) {
                    $q->where('is_active', false);
                });
        })
            ->whereNotNull('parent_id')
            ->whereHas('day', function (Builder $query) {
                $query->whereDate('date', '>=', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->whereDate('date', now()->toDateString())
                            ->whereTime('from_time', '>', now()->toTimeString());
                    });
            });
    }

    //---------------------Scopes-------------------------------------

}
