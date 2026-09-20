<?php

namespace App\Enums;

class DonationTypeEnum
{

    const PART = 'part';
    const ALL = 'all';

    public static function toArray(){
        return [
            self::PART,
            self::ALL,
        ];
    }

}
