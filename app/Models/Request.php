<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Request extends Model
{
    protected $appends = ['user_name'];

    protected $fillable = [
        'user_id',
        'disease_name',
        'doctor_name',
        'visit_date',
        'recipe',
        'status',
        'reject_reason',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];


    /** @noinspection PhpUnused */
    public function getRecipeAttribute($value)
    {
        return $value ? \env('APP_URL').'/storage/'.$value : null;
    }

    /** @noinspection PhpUnused */
    public function getUserNameAttribute()
    {
        $userName = $this->User?->full_name;
        if ($this->relationLoaded('User')){
            $this->unsetRelation('User');
        }
        return $userName;
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function Recipe(): HasOne
    {
        return $this->hasOne(Recipe::class, 'request_id');
    }
}
