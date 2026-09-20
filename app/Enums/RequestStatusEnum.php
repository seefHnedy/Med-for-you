<?php

namespace App\Enums;

enum RequestStatusEnum
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';


    public static function toArray(){
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED
        ];
    }
}
