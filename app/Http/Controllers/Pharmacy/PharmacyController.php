<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\AddPharmacyRequest;
use App\Http\Requests\Pharmacy\GetPharmaciesRequest;
use App\Http\Requests\Pharmacy\PharmacyIdRequest;
use App\Http\Requests\Pharmacy\PharmacyLoginRequest;
use App\Http\Requests\Pharmacy\UpdatePharmacyRequest;
use App\Service\Pharmacy\PharmacyService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;

class PharmacyController extends Controller implements HasMiddleware
{
    public function __construct(public PharmacyService $service)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('admin.type:superAdmin,admin', only: ['AddPharmacy','DeletePharmacy','UpdatePharmacy']),
        ];
    }


    public function Login(PharmacyLoginRequest $request)
    {
        $data = Arr::only($request->validated(), ['email', 'password']);
        $pharmacy = $this->service->Login($data);
        return $this->sendResponse('Pharmacy Login Successfully', $pharmacy);
    }

    public function Logout()
    {
        $this->service->Logout();
        return $this->sendResponse('Pharmacy Logout Successfully', null);
    }

    public function AddPharmacy(AddPharmacyRequest $request)
    {
        $data = Arr::only($request->validated(), ['name', 'email', 'password', 'phone', 'city_id', 'location', 'location_link', 'location_longitude', 'location_latitude', 'owner_name', 'owner_phone', 'license', 'license_expire_at']);
        $this->service->AddPharmacy($data);
        return $this->sendResponse('Pharmacy Added Successfully');
    }

    public function GetPharmacies(GetPharmaciesRequest $request)
    {
        $data = Arr::only($request->validated(),['search']);
        $pharmacies = $this->service->GetPharmacies($data);
        return $this->sendPagination('Pharmacy Fetched Successfully', $pharmacies);
    }

    public function DeletePharmacy(PharmacyIdRequest $request){
        $data = Arr::only($request->validated(),['pharmacy_id']);
        $this->service->DeletePharmacy($data);
        return $this->sendResponse('Pharmacy Deleted Successfully');
    }

    public function UpdatePharmacy(UpdatePharmacyRequest $request){
        $data = Arr::only($request->validated(),['pharmacy_id' ,'email' ,'password' ,'phone' ,'owner_name' ,'owner_phone']);
        $this->service->UpdatePharmacy($data);
        return $this->sendResponse('Pharmacy Updated Successfully');
    }
}
