<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PharmacyRequest extends Model
{

    protected $appends = ['pharmacy_name'];

    protected $fillable = [
        'pharmacy_id',
        'recipe_id',
        'medicines_qty',
        'total_price',
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
    public function getPharmacyNameAttribute()
    {
        $pharmacyName = $this->Pharmacy?->name;
        if ($this->relationLoaded('Pharmacy')){
            $this->unsetRelation('Pharmacy');
        }
        return $pharmacyName;
    }

    protected static function booted()
    {
        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });

        static::creating(function ($pharmacyRequest) {
            $pharmacyRequest->otp_expire_at = now()->addMinutes(10);
        });
    }

    public function Pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id')->withTrashed();
    }

    public function Recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'recipe_id');
    }

    public function PharmacyRequestMedicines(): HasMany
    {
        return $this->hasMany(PharmacyRequestMedicine::class, 'pharmacy_request_id');
    }
}
