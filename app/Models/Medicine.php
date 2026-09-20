<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description',
        'factory',
        'composition',
        'concentration',
        'pharmaceutical_form',
        'package',
        'price',
        'order_qty',
        'available_qty'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'available_qty',
    ];

    public function RecipeMedicines(): HasMany
    {
        return $this->hasMany(RecipeMedicine::class, 'medicine_id');
    }

    public function Donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'medicine_id');
    }

    public function PharmacyRequestMedicines(): HasMany
    {
        return $this->hasMany(PharmacyRequestMedicine::class, 'medicine_id');
    }
}
