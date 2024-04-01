<?php

namespace App\Models;

use App\Constants\PatientSocialStatusConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Patient extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, SoftDeletes, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['user_id', 'parent_id', 'date_of_birth', 'national_id', 'social_status', 'is_active'];
    protected array $filters = ['keyword','parent', 'active'];
    protected array $searchable = ['user.name'];
    protected array $dates = ['date_of_birth'];
    public array $filterModels = [];
    public array $filterCustom = ['socialStatuses'];
    public array $translatable = [];
    protected $with = ['user'];
    protected $casts = [
        'social_status' => PatientSocialStatusConstants::class
    ];
    protected array $definedRelations = ['consultations'];
    //---------------------relations-------------------------------------
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatives(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfParent($query, $value = null)
    {
        $value = $value ?? auth()->user()->patient?->id;
        return $query->where('parent_id', $value);
    }
    //---------------------Scopes-------------------------------------

    public static function socialStatuses(): array
    {
        return PatientSocialStatusConstants::valuesCollection();
    }
    public function age()
    {
        return Carbon::parse($this->attributes['date_of_birth'])->age;
    }
}
