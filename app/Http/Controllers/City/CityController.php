<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Service\City\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(public CityService $service)
    {
    }

    public function getCities(){
        $cities = $this->service->getCities();
        return $this->sendResponse('Cities Found Successfully',$cities);
    }
}
