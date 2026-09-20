<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiaryAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'personal_image',
        'id_front_image',
        'id_back_image',
        'salary_statement_image',
        'medical_report_image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /** @noinspection PhpUnused */
    public function getPersonalImageAttribute($value)
    {
        return $value ? \env('APP_URL').'/storage/'.$value : null;
    }

    public function getIdFrontImageAttribute($value)
    {
        return $value ? \env('APP_URL').'/storage/'.$value : null;
    }

    /** @noinspection PhpUnused */
    public function getIdBackImageAttribute($value)
    {
        return $value ? \env('APP_URL').'/storage/'.$value : null;
    }

    /** @noinspection PhpUnused */
    public function getSalaryStatementImageAttribute($value)
    {
        return $value ? \env('APP_URL').'/storage/'.$value : null;
    }

    /** @noinspection PhpUnused */
    public function getMedicalReportImageAttribute($value)
    {
        return $value ? \env('APP_URL').'/storage/'.$value : null;
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
