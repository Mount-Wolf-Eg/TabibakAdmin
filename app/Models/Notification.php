<?php

namespace App\Models;

use App\Constants\NotificationTypeConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['title', 'body', 'type', 'redirect_type', 'redirect_id', 'data'];
    protected array $filters = ['keyword', 'type', 'user'];
    protected array $searchable = [];
    protected array $dates = [];
    public array $filterModels = [];
    public array $filterCustom = [];
    public array $translatable = [];
    protected $casts = [
        'data' => 'array',
        'type' => NotificationTypeConstants::class
    ];

    //---------------------relations-------------------------------------
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('read_at')->withTimestamps();
    }

    public function redirect(): MorphTo
    {
        return $this->morphTo();
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->whereHas('users', function ($query) use ($userId){
            $query->where('users.id', $userId);
        });
    }
    //---------------------Scopes-------------------------------------

}
