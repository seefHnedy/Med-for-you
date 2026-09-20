<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeMedicine extends Model
{
    protected $appends = ['medicine_name','pharmacy_name'];

    protected $fillable = [
        'recipe_id',
        'medicine_id',
        'price',
        'qty',
        'total_price',
        'pharmacy_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'status',
    ];


    public function getMedicineNameAttribute()
    {
        $medicineName = $this->Medicine?->name_ar;
        if ($this->relationLoaded('Medicine')){
            $this->unsetRelation('Medicine');
        }
        return $medicineName;
    }

    /** @noinspection PhpUnused */
    public function getPharmacyNameAttribute()
    {
        $pharmacyName = $this->Pharmacy?->name;
        if ($this->relationLoaded('Pharmacy')){
            $this->unsetRelation('Pharmacy');
        }
        return $pharmacyName;
    }


    public function Recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'recipe_id');
    }

    public function Medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id')->withTrashed();
    }

    public function Pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id')->withTrashed();
    }
}
