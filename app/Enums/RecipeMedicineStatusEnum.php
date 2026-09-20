<?php

namespace App\Enums;

enum RecipeMedicineStatusEnum
{
    const INITIAL = 'initial';
    const IN_PROCESS = 'in_process';
    const FINISHED = 'finished';

    public static function toArray(){
        return [
            self::INITIAL,
            self::IN_PROCESS,
            self::FINISHED,
        ];
    }
}
