<?php

namespace App\Http\Controllers\PharmacyRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\PharmacyRequest\AddPharmacyRequestRequest;
use App\Http\Requests\PharmacyRequest\GetPharmacyRequestsAdminRequest;
use App\Http\Requests\PharmacyRequest\GetPharmacyRequestsRequest;
use App\Http\Requests\PharmacyRequest\VerifyPharmacyRequestRequest;
use App\Service\PharmacyRequest\PharmacyRequestService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;

class PharmacyRequestController extends Controller implements HasMiddleware
{
    public function __construct(public PharmacyRequestService $service)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('admin.type:superAdmin,admin', only: ['GetPharmacyRequestsAdmin']),
        ];
    }

    public function AddPharmacyRequest(AddPharmacyRequestRequest $request)
    {
        $data = Arr::only($request->validated(), ['recipe_id', 'recipe_medicines']);
        $pharmacyRequest = $this->service->AddPharmacyRequest($data);
        return $this->sendResponse('Pharmacy Request Added Successfully', $pharmacyRequest);
    }

    public function VerifyPharmacyRequest(VerifyPharmacyRequestRequest $request){
        $data = Arr::only($request->validated(), ['pharmacy_id' ,'pharmacy_request_id' ,'otp']);
        $this->service->VerifyPharmacyRequest($data);
        return $this->sendResponse('Pharmacy Request Verified Successfully');
    }

    public function GetPharmacyRequests(GetPharmacyRequestsRequest $request){
        $data = Arr::only($request->validated(), ['pharmacy_id' ,'status']);
        $pharmacyRequests = $this->service->GetPharmacyRequests($data);
        return $this->sendPagination('Pharmacy Request Fetched Successfully',$pharmacyRequests);
    }

    public function GetPharmacyRequestsAdmin(GetPharmacyRequestsAdminRequest $request){
        $data = Arr::only($request->validated(), ['pharmacy_id' ,'status']);
        $pharmacyRequests = $this->service->GetPharmacyRequestsAdmin($data);
        return $this->sendPagination('Pharmacy Request Fetched Successfully',$pharmacyRequests);
    }
}
