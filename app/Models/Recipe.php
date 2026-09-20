<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{

    protected $fillable = [
        'request_id',
        'medicines_qty',
        'total_price',
        'priority',
        'status',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function Request(): BelongsTo
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    public function RecipeMedicines(): HasMany
    {
        return $this->hasMany(RecipeMedicine::class, 'recipe_id');
    }

    public function PharmacyRequests(): HasMany
    {
        return $this->hasMany(PharmacyRequest::class, 'recipe_id');
    }

}
