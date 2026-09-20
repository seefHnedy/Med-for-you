<?php

namespace App\Enums;

enum AccountStatusEnum
{
    const OTP = 'otp';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';


    public static function toArray(){
        return [
            self::OTP,
            self::PENDING,
            self::APPROVED,
            self::REJECTED
        ];
    }

    public static function approveOrReject(){
        return [
            self::APPROVED,
            self::REJECTED
        ];
    }

    public static function RejectAccess(){
        return [
            self::OTP,
            self::PENDING,
            self::REJECTED
        ];
    }
}
