<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['question', 'answer', 'faq_subject_id', 'is_active'];
    protected array $filters = ['keyword', 'active', 'activeSubject', 'faqSubject'];
    protected array $searchable = ['question', 'answer'];
    protected array $dates = [];
    public array $filterModels = ['FaqSubject'];
    public array $filterCustom = [];
    public array $translatable = ['question', 'answer'];

    //---------------------relations-------------------------------------

    public function faqSubject(): BelongsTo
    {
        return $this->belongsTo(FaqSubject::class);
    }

    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfActiveSubject($query)
    {
        return $query->whereHas('faqSubject', function ($query) {
            $query->ofActive();
        });
    }

    public function scopeOfFaqSubject($query, $faqSubjectId)
    {
        return $query->where('faq_subject_id', $faqSubjectId);
    }
    //---------------------Scopes-------------------------------------

}
