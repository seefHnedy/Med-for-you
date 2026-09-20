<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class OTP extends Model
{
    protected $fillable = [
        'user_id',
        'otp_type',
        'otp',
        'otp_expire_at',
    ];

    protected $casts = [
        'otp' => 'hashed',
    ];

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
}
