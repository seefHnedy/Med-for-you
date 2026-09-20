<?php

namespace App\Enums;

class PharmacyRequestStatusEnum
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
