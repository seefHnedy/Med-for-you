<?php

namespace App\Service\City;

use App\Models\City;

class CityService
{

    public function getCities()
    {
        $cities = City::all();
        return $cities;
    }
}
