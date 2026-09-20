<?php

namespace App\Enums;

enum RecipePriorityEnum
{

    const NORMAL = 'normal';
    const URGENT = 'urgent';
    const EMERGENCY = 'emergency';

    public static function toArray(){
        return [
            self::NORMAL,
            self::URGENT,
            self::EMERGENCY
        ];
    }
}
