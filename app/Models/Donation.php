<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{

    protected $appends = ['medicine_name'];

    protected $fillable = [
        'user_id',
        'medicine_id',
        'medicine_price',
        'qty',
        'donation_price',
        'otp',
        'otp_expire_at',
        'status',
    ];

    protected $hidden = [
        'updated_at',
        'otp',
        'otp_expire_at',
    ];

    protected $casts = [
        'otp' => 'hashed',
    ];

    /** @noinspection PhpUnused */
    public function getMedicineNameAttribute($key)
    {
        $medicineName = $this->Medicine?->name_ar;
        if ($this->relationLoaded('Medicine')){
            $this->unsetRelation('Medicine');
        }
        return $medicineName;
    }

    protected static function booted()
    {
        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });

        static::creating(function ($otp) {
            $otp->otp_expire_at = now()->addMinutes(10);
        });

    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function Medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id')->withTrashed();
    }
}
