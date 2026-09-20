<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function Users(): HasMany
    {
        return $this->hasMany(User::class, 'city_id');
    }

    public function Pharmacies(): HasMany
    {
        return $this->hasMany(Pharmacy::class, 'city_id');
    }
}
