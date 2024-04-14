<?php

namespace App\Models;

use App\Constants\ComplaintTypeConstants;
use App\Constants\FileConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, SoftDeletes, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['author_id','title', 'content', 'medical_speciality_id', 'publish_date',
        'publisher_id', 'views', 'likes', 'dislikes', 'reports', 'is_active'];
    protected array $filters = ['keyword', 'medicalSpeciality', 'isPublished', 'isMine', 'active'];
    protected array $searchable = ['title', 'content'];
    protected array $dates = ['publish_date'];
    public array $filterModels = ['MedicalSpeciality'];
    public array $filterCustom = ['complaintsTypes'];
    public array $translatable = ['title', 'content'];

    //---------------------relations-------------------------------------
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function mainImage(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', FileConstants::FILE_TYPE_ARTICLE_MAIN_IMAGE);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function medicalSpeciality(): BelongsTo
    {
        return $this->belongsTo(MedicalSpeciality::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->where('type', FileConstants::FILE_TYPE_ARTICLE_IMAGES);
    }

    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfMedicalSpeciality($query, $medicalSpecialityId)
    {
        return $query->whereIn('medical_speciality_id', (array)$medicalSpecialityId);
    }

    public function scopeIsPublished($query)
    {
        return $query->whereNotNull('publish_date');
    }

    public function scopeOfIsMine($query)
    {
        return $query->where('author_id', auth()->id() ?? auth('sanctum')->id());
    }
    //---------------------Scopes-------------------------------------

    //---------------------Attributes-------------------------------------
    public function authLikeStatus(): Attribute
    {
        return Attribute::get(function () {
            return $this->likes->contains('user_id', auth()->id() ?? auth('sanctum')->id());
        });
    }
    //---------------------Attributes-------------------------------------

    public static function complaintsTypes(): array
    {
        return ComplaintTypeConstants::valuesCollection();
    }
}
