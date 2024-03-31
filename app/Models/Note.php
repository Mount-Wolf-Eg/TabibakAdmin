<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Note extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, SoftDeletes, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['text', 'notable_id', 'notable_type', 'user_id'];
    protected array $filters = ['keyword', 'active'];
    protected array $searchable = ['text'];
    protected array $dates = [];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];
    public const PERMISSIONS_NOT_APPLIED = false;

    //---------------------relations-------------------------------------
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject',
            'subject_type', 'subject_id');
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------

    //---------------------Scopes-------------------------------------

    public function isMine(): bool
    {
        return $this->user_id === auth()->id();
    }

    //---------------------Attributes---------------------------------
}
