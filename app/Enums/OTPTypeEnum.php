<?php

namespace App\Enums;

enum OTPTypeEnum
{
    const RESET = 'reset';
    const REGISTER = 'register';


    public static function toArray()
    {
        return [
            self::RESET,
            self::REGISTER
        ];
    }
}
