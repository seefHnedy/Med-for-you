<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $appends = ['city_name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'birthday',
        'city_id',
        'phone',
        'email',
        'password',
        'fcm_token',
        'type',
        'status',
    ];

    /** @noinspection PhpUnused */
    public function getCityNameAttribute()
    {
        $cityName = $this?->City?->name;
        if ($this->relationLoaded('City')) {
            $this->unsetRelation('City');
        }
        return $cityName;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'password',
        'fcm_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /** @noinspection PhpUnused */
    public function BeneficiaryData(): HasOne
    {
        return $this->hasOne(BeneficiaryData::class, 'user_id');
    }

    /** @noinspection PhpUnused */
    public function BeneficiaryAttachment(): HasOne
    {
        return $this->hasOne(BeneficiaryAttachment::class, 'user_id');
    }

    public function OTPs(): HasMany
    {
        return $this->hasMany(OTP::class, 'user_id');
    }

    public function Requests(): HasMany
    {
        return $this->hasMany(Request::class, 'user_id');
    }

    public function Donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'user_id');
    }

    public function Notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
