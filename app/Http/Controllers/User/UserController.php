<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeUserStatusRequest;
use App\Http\Requests\User\GetProfileInfoRequest;
use App\Http\Requests\User\GetUsersRequest;
use App\Http\Requests\User\ResendOTPRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UserIdRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\VerifyEmailRequest;
use App\Service\User\UserService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;

class UserController extends Controller implements HasMiddleware
{

    public function __construct(public UserService $service)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('admin.type:superAdmin,admin', only: ['GetUsers', 'DeleteUser','ChangeUserStatus','GetOneUser']),
        ];
    }

    public function RegisterUser(UserRegisterRequest $request)
    {
        $commonData = Arr::only($request->validated(), ['full_name', 'birthday', 'city_id', 'phone', 'email', 'password', 'type']);
        $beneficiaryData = Arr::only($request->validated(), ['father_name', 'mother_name', 'national_number', 'work', 'current_address', 'permanent_address', 'health_status', 'disease_name', 'disability_status', 'family_income']);
        $beneficiaryAttachments = Arr::only($request->validated(), ['personal_image', 'id_front_image', 'id_back_image', 'salary_statement_image', 'medical_report_image']);
        $this->service->RegisterUser($commonData, $beneficiaryData, $beneficiaryAttachments);
        return $this->sendResponse('User Registered Successfully');
    }

    public function Login(UserLoginRequest $request)
    {
        $data = Arr::only($request->validated(), ['email', 'password','fcm_token']);
        $user = $this->service->Login($data);
        return $this->sendResponse('User Login Successfully', $user);
    }

    public function Logout()
    {
        $this->service->Logout();
        return $this->sendResponse('User Logout Successfully', null);
    }

    public function VerifyEmail(VerifyEmailRequest $request)
    {
        $data = Arr::only($request->validated(), ['email', 'otp']);
        $this->service->VerifyEmail($data);
        return $this->sendResponse('Email Verified Successfully');
    }

    public function ResendOtp(ResendOTPRequest $request)
    {
        $data = Arr::only($request->validated(), ['email', 'otp_type']);
        $this->service->ResendOtp($data);
        return $this->sendResponse('Resend OTP Successfully');
    }

    public function ResetPassword(ResetPasswordRequest $request){
        $data = Arr::only($request->validated(), ['email', 'password','otp']);
        $this->service->ResetPassword($data);
        return $this->sendResponse('Password Reset Successfully');
    }

    public function GetProfileInfo(GetProfileInfoRequest $request){
        $data = Arr::only($request->validated(), ['user_id']);
        $user = $this->service->GetProfileInfo($data);
        return $this->sendResponse('Profile Get Successfully',$user);
    }

    public function GetUsers(GetUsersRequest $request){
        $data = Arr::only($request->validated(), ['status','search','type']);
        $users = $this->service->GetUsers($data);
        return $this->sendPagination('Users Fetched Successfully',$users);
    }

    public function DeleteUser(UserIdRequest $request){
        $data = Arr::only($request->validated(),['user_id']);
        $this->service->DeleteUser($data);
        return $this->sendResponse('User Deleted Successfully');
    }

    public function ChangeUserStatus(ChangeUserStatusRequest $request){
        $data = Arr::only($request->validated(),['user_id','status']);
        $this->service->ChangeUserStatus($data);
        return $this->sendResponse('User Status Changed Successfully');
    }

    public function GetOneUser(UserIdRequest $request){
        $data = Arr::only($request->validated(),['user_id']);
        $user = $this->service->GetOneUser($data);
        return $this->sendResponse('User Fetched Successfully',$user);
    }
}
