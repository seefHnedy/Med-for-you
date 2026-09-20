<?php

namespace App\Enums;

enum DonationStatusEnum
{
    const APPROVED = 'approved';
    const REJECTED = 'rejected';


    public static function toArray(){
        return [
            self::APPROVED,
            self::REJECTED
        ];
    }
}
