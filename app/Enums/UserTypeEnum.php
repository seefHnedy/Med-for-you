<?php

namespace App\Enums;

enum UserTypeEnum
{
    const DONOR = 'donor';
    const BENEFICIARY = 'beneficiary';

    public static function toArray()
    {
        return [
            self::BENEFICIARY,
            self::DONOR
        ];
    }
}
