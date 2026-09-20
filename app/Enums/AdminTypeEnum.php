<?php

namespace App\Enums;

enum AdminTypeEnum
{
    const SUPER_ADMIN = 'superAdmin';
    const ADMIN = 'admin';
    const AUDITOR = 'auditor';


    public static function toArray()
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::AUDITOR,
        ];
    }
}
