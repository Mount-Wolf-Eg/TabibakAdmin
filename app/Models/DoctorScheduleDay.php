<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class DoctorScheduleDay extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['doctor_id', 'date', 'has', 'is_active'];
    protected array $filters = ['keyword', 'active', 'doctor', 'afterNowDateTime'];
    protected array $searchable = [];
    protected array $dates = ['date'];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];

    //---------------------relations-------------------------------------
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scheduleDayShifts(): HasMany
    {
        return $this->hasMany(DoctorScheduleDayShift::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(DoctorScheduleDayShift::class)->whereNull('parent_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(DoctorScheduleDayShift::class)->whereNotNull('parent_id');
    }

    public function availableSlots(): HasMany
    {
        return $this->hasMany(DoctorScheduleDayShift::class)->ofAvailableSlots();
    }

    public function nearestAvailableSlot(): HasOne
    {
        return $this->hasOne(DoctorScheduleDayShift::class)->ofAvailableSlots();
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------

    public function scopeOfDoctor($query, $value)
    {
        return $query->whereIn('doctor_id', (array)$value);
    }

    public function scopeOfMine($query)
    {
        return $query->ofDoctor(auth()->user()->doctor?->id);
    }

    public function scopeOfAfterNowDateTime($query)
    {
        return $query->where(function ($q) {
            $q->whereDate('date', '>', now()->toDateString())
                ->orWhere(function ($q) {
                    $q->whereDate('date', now()->toDateString())
                        ->whereHas('availableSlots', function ($q) {
                            $q->where('from_time', '>', now()->toTimeString());
                        });
                });
        });
    }
    //---------------------Scopes-------------------------------------

    //---------------------Attributes-------------------------------------

    public function dayName(): Attribute
    {
        return Attribute::make(function ($value) {
            return $this->date->format('l');
        });
    }

}
