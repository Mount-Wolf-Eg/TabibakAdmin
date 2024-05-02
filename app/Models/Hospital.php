<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Hospital extends Model
{
    use ModelTrait, SearchTrait, SoftDeletes, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['name', 'is_active'];
    protected array $filters = ['keyword', 'name'];
    protected array $searchable = ['name'];
    protected array $dates = [];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = ['name'];

    //---------------------relations-------------------------------------

    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfName($query, $name)
    {
        return $query->where('name', $name);
    }
    //---------------------Scopes-------------------------------------

}
