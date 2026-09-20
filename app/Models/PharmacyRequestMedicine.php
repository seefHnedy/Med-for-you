<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PharmacyRequestMedicine extends Model
{

    protected $appends = ['medicine_name'];

    protected $fillable = [
        'pharmacy_request_id',
        'medicine_id',
        'price',
        'qty',
        'total_price',
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function getMedicineNameAttribute()
    {
        $medicineName = $this->Medicine?->name_ar;
        if ($this->relationLoaded('Medicine')){
            $this->unsetRelation('Medicine');
        }
        return $medicineName;
    }

    public function PharmacyRequest(): BelongsTo
    {
        return $this->belongsTo(PharmacyRequest::class, 'pharmacy_request_id');
    }

    public function Medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id')->withTrashed();
    }

}
