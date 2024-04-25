<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AcademicDegree extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['name', 'description', 'is_active'];
    protected array $filters = ['keyword', 'active'];
    protected array $searchable = ['name'];
    protected array $dates = [];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = ['name', 'description'];
    protected array $definedRelations = ['doctors'];

    //---------------------relations-------------------------------------
    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------

    //---------------------Scopes-------------------------------------

}
