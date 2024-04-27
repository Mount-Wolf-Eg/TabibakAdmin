<?php

namespace App\Models;

use App\Constants\FileConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Vendor extends Model
{
    use SoftDeletes, ModelTrait, SearchTrait, HasTranslations;
    public const ADDITIONAL_PERMISSIONS = [];
    protected $fillable = ['user_id', 'vendor_type_id', 'city_id', 'address', 'is_active'];
    protected array $filters = ['keyword', 'active', 'vendorType', 'vendorService', 'city'];
    protected array $searchable = ['user.name'];
    protected array $dates = [];
    public array $filterModels = ['VendorService', 'VendorType', 'City'];
    public array $filterCustom = [];
    public array $translatable = [];
    public $with = ['user', 'vendorType'];

    //---------------------relations-------------------------------------
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function vendorType(): BelongsTo
    {
        return $this->belongsTo(VendorType::class);
    }

    public function vendorServices(): BelongsToMany
    {
        return $this->belongsToMany(VendorService::class, 'service_vendor');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function icon(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', FileConstants::FILE_TYPE_VENDOR_ICON)->latest();
    }
    //---------------------relations-------------------------------------

    //---------------------Scopes-------------------------------------
    public function scopeOfVendorType($query, $type)
    {
        return $query->whereIn('vendor_type_id', (array)$type);
    }

    public function scopeOfVendorService($query, $value)
    {
        return $query->whereHas('vendorServices', function ($q) use ($value) {
            $q->whereIn('vendor_service_id', (array)$value);
        });
    }

    public function scopeOfCity($query, $value)
    {
        return $query->whereIn('city_id', (array)$value);
    }
    //---------------------Scopes-------------------------------------

}
