<?php

namespace App\Models;

use App\Constants\FileConstants;
use App\Constants\RoleNameConstants;
use App\Constants\UserGenderConstants;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTranslations,
        HasRoles, HasPermissions, ModelTrait, SearchTrait, SoftDeletes;

	protected $fillable = ['name', 'username', 'email', 'password', 'phone', 'gender',
        'city_id', 'date_of_birth', 'address','wallet', 'verification_code',
        'phone_verified_at', 'is_active'];
    protected array $filters = ['keyword', 'role', 'roleName', 'email', 'active', 'onlyUsersRoles'];
    public array $filterModels = ['Role'];
    public array $filterCustom = [];
    protected array $searchable = ['name', 'email'];
    protected array $dates = ['date_of_birth'];
    public array $translatable = ['name'];
    protected $with = ['avatar', 'city'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'gender' => UserGenderConstants::class
    ];

    //---------------------relations-------------------------------------
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }
    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }
    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', FileConstants::FILE_TYPE_USER_AVATAR)->latest();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    //---------------------relations-------------------------------------
    // ----------------------- Scopes -----------------------
    public function getRoleId()
    {
        $role = $this->roles()->first();
        return $role ? $role->id : null;
    }

    public function getDoctorIsActiveAttribute()
    {
        return $this->is_active && $this->doctor?->is_active && $this->doctor?->request_status->value == 2 ? 1 : 0;
    }

    public function scopeOfRole($query, $value)
    {
        return $query->whereHas('roles', function ($query) use ($value) {
            $query->where('id', $value);
        });
    }

    public function scopeOfOnlyUsersRoles($query)
    {
        $roles = [RoleNameConstants::DOCTOR->value, RoleNameConstants::PATIENT->value,
            RoleNameConstants::VENDOR->value];
        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->whereNotIn('name', $roles);
        });
    }

    public function scopeOfRoleName($query, $value)
    {
        $value = (array) $value;
        return $query->whereHas('roles', function ($query) use ($value) {
            $query->whereIn('name', $value);
        });
    }
    // ----------------------- Scopes -----------------------

    public function setPasswordAttribute($input): void
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function isVerified(): Attribute
    {
        return Attribute::make(fn($value) => !is_null($this->phone_verified_at)
            || !is_null($this->email_verified_at));
    }

    public function firstName(): Attribute
    {
        return Attribute::make(fn($value) => explode(' ', $this->name)[0]);
    }

    public function avatarAssetDefaultUrl(): Attribute
    {
        return Attribute::make(fn($value) => $this->avatar->asset_url ?? asset('assets/images/users/user-dummy-img.jpg'));
    }

    public function age(): Attribute
    {
        return new Attribute(function () {
            return Carbon::parse($this->date_of_birth)->age;
        });
    }

    public function routeNotificationForFcm(): array|string
    {
        return $this->getDeviceTokens();
    }

    public function getDeviceTokens(): array
    {
        return $this->tokens->whereNotNull('fcm_token')->unique('fcm_token')->pluck('fcm_token')->toArray();
    }

}
