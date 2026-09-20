<?php

namespace App\Http\Controllers\Donation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donation\AddDonationRequest;
use App\Http\Requests\Donation\GetMyDonationsRequest;
use App\Http\Requests\Donation\GetUserDonationsRequest;
use App\Http\Requests\Donation\VerifyDonationRequest;
use App\Service\Donation\DonationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;

class DonationController extends Controller implements HasMiddleware
{
    public function __construct(public DonationService $service)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('admin.type:superAdmin,admin', only: ['GetUserDonations']),
        ];
    }


    public function AddDonation(AddDonationRequest $request){
        $data = Arr::only($request->validated(),['user_id' ,'medicine_id' ,'type' ,'qty']);
        $donation = $this->service->AddDonation($data);
        return $this->sendResponse('The Donation Request Added Successfully',$donation);
    }

    public function VerifyDonation(VerifyDonationRequest $request){
        $data = Arr::only($request->validated(),['user_id' ,'donation_id' ,'otp']);
        $this->service->VerifyDonation($data);
        return $this->sendResponse('The Donation Request Verified Successfully');
    }

    public function GetMyDonations(GetMyDonationsRequest $request){
        $data = Arr::only($request->validated(),['user_id','status']);
        $donations = $this->service->GetMyDonations($data);
        return $this->sendPagination('The Donation Request Verified Successfully',$donations);
    }

    public function GetUserDonations(GetUserDonationsRequest $request){
        $data = Arr::only($request->validated(),['user_id','status']);
        $donations = $this->service->GetUserDonations($data);
        return $this->sendPagination('Donations Fetched Successfully',$donations);
    }
}
