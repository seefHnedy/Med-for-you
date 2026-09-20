<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiaryData extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'father_name',
        'mother_name',
        'national_number',
        'work',
        'current_address',
        'permanent_address',
        'health_status',
        'disease_name',
        'disability_status',
        'family_income',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
