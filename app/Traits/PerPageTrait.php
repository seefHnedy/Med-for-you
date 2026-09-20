<?php

namespace App\Traits;

trait PerPageTrait
{
    public function getPerPage(){
        $perPage = \request()->header('perPage') ?? 10;
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, [
            'options' => ['default' => 10, 'min_range' => 1]
        ]);
        return $perPage;
    }
}
