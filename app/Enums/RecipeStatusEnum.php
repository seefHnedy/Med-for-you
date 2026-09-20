<?php

namespace App\Enums;

enum RecipeStatusEnum
{
    const INITIAL = 'initial';
    const FINISHED = 'finished';

    public static function toArray(){
        return [
            self::INITIAL,
            self::FINISHED,
        ];
    }
}
