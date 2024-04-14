<?php

namespace App\Models;

use App\Constants\PatientBloodTypeConstants;
use App\Constants\PatientSocialStatusConstants;
use App\Constants\UserGenderConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Patient extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, SoftDeletes, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['user_id', 'parent_id', 'national_id',
        'other_diseases', 'latest_surgeries', 'weight', 'height', 'blood_type',
        'social_status', 'is_active'];
    protected array $filters = ['keyword','parent', 'active'];
    protected array $searchable = ['user.name'];
    protected array $dates = [];
    public array $filterModels = ['Disease'];
    public array $filterCustom = ['socialStatuses', 'bloodTypes', 'genders'];
    public array $translatable = [];
    protected $with = ['user'];
    protected $casts = [
        'social_status' => PatientSocialStatusConstants::class,
        'blood_type' => PatientBloodTypeConstants::class
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

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'disease_patient')->withTimestamps();
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

    public static function bloodTypes(): array
    {
        return PatientBloodTypeConstants::valuesCollection();
    }

    public static function genders(): array
    {
        return UserGenderConstants::valuesCollection();
    }

}
