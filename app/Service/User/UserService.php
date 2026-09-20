<?php

namespace App\Service\User;

use App\Enums\AccountStatusEnum;
use App\Enums\OTPTypeEnum;
use App\Enums\UserTypeEnum;
use App\Exceptions\ValidationException;
use App\Mail\SendOTPMail;
use App\Models\BeneficiaryAttachment;
use App\Models\BeneficiaryData;
use App\Models\OTP;
use App\Models\User;
use App\Traits\FileTrait;
use App\Traits\PerPageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    use FileTrait, PerPageTrait;

    public function RegisterUser($data, $beneficiaryData = [], $beneficiaryAttachments = [])
    {
        $user = User::create($data);
        if ($data['type'] === UserTypeEnum::BENEFICIARY) {
            $beneficiaryData['user_id'] = $user->id;
            $beneficiaryAttachments['user_id'] = $user->id;
            BeneficiaryData::create($beneficiaryData);
            $path = "Attachments/User/{$user->id}/";
            $beneficiaryAttachments['personal_image'] = $this->uploadFile($beneficiaryAttachments['personal_image'], $path);
            $beneficiaryAttachments['id_front_image'] = $this->uploadFile($beneficiaryAttachments['id_front_image'], $path);
            $beneficiaryAttachments['id_back_image'] = $this->uploadFile($beneficiaryAttachments['id_back_image'], $path);
            $beneficiaryAttachments['salary_statement_image'] = $this->uploadFile($beneficiaryAttachments['salary_statement_image'], $path);
            $beneficiaryAttachments['medical_report_image'] = $this->uploadFile($beneficiaryAttachments['medical_report_image'], $path);
            BeneficiaryAttachment::create($beneficiaryAttachments);
        }
        $otp = \rand(111111, 999999);
        Mail::to($user->email)->send(new SendOTPMail($otp));
        OTP::create([
            'user_id' => $user->id,
            'otp_type' => OTPTypeEnum::REGISTER,
            'otp' => $otp,
        ]);
        return $user;
    }

    public function Login($data)
    {
        $user = User::where('email', $data['email'])->first();
        if (in_array($user->status, AccountStatusEnum::RejectAccess())) {
            throw new ValidationException('Access Denied , Your Account Status is : ' . $user->status);
        }
        if (!Hash::check($data['password'], $user->password)) {
            throw new ValidationException('user password incorrect');
        }
        if (isset($data['fcm_token']) && !empty($data['fcm_token'])) {
            $user->update(['fcm_token' => $data['fcm_token']]);
        }
        $user->tokens()
            ->whereJsonContains('scopes', 'User')
            ->delete();
        $user['token'] = $user->createToken('authToken', ['User'])->accessToken;
        $user->load(['BeneficiaryData', 'BeneficiaryAttachment']);
        return $user;
    }


    public function Logout()
    {
        $user = \auth('User')->user();
        $user->update(['fcm_token'=>null]);
        $user->tokens()
            ->whereJsonContains('scopes', 'User')
            ->delete();
    }

    public function VerifyEmail($data)
    {
        $user = User::where('email', $data['email'])->first();
        $otp = OTP::where([
            'user_id' => $user->id,
            'otp_type' => OTPTypeEnum::REGISTER,
        ])->first();

        if (!$otp) {
            throw new ValidationException('No OTP code sent to this email');
        }
        if (Carbon::parse($otp->otp_expire_at)->isPast()) {
            throw new ValidationException('OTP code expired');
        }
        if (!Hash::check($data['otp'], $otp->otp)) {
            throw new ValidationException('OTP code incorrect');
        }
        if ($user->type===UserTypeEnum::BENEFICIARY) {
            $status =  AccountStatusEnum::PENDING;
        }else{
            $status =  AccountStatusEnum::APPROVED;
        }
        $user->update([
            'status' => $status,
        ]);
    }

    public function ResendOtp($data)
    {
        $user = User::where('email', $data['email'])->first();
        $otp = OTP::where([
            'user_id' => $user->id,
            'otp_type' => $data['otp_type'],
        ])->first();
        if ($otp && !Carbon::parse($otp->otp_expire_at)->isPast()) {
            throw new ValidationException('You have OTP Code , try again later .');
        }
        $otp = \rand(111111, 999999);
        Mail::to($user->email)->send(new SendOTPMail($otp));
        OTP::create([
            'user_id' => $user->id,
            'otp_type' => $data['otp_type'],
            'otp' => $otp,
        ]);
    }

    public function ResetPassword($data)
    {
        $user = User::where('email', $data['email'])->first();
        $otp = OTP::where([
            'user_id' => $user->id,
            'otp_type' => OTPTypeEnum::RESET,
        ])->first();
        if (!$otp) {
            throw new ValidationException('No OTP code sent to this email');
        }
        if (Carbon::parse($otp->otp_expire_at)->isPast()) {
            throw new ValidationException('OTP code expired');
        }
        if (!Hash::check($data['otp'], $otp->otp)) {
            throw new ValidationException('OTP code incorrect');
        }
        $user->update([
            'password' => $data['password'],
        ]);

    }

    public function GetProfileInfo($data)
    {
        $user = \auth('User')->user();
        $user->load(['BeneficiaryData', 'BeneficiaryAttachment']);
        return $user;
    }

    public function GetUsers($data)
    {
        $perPage = $this->getPerPage();
        $users = User::query();
        $users = $users->with(['BeneficiaryData', 'BeneficiaryAttachment']);

        if (isset($data['search'])) {
            $users = $users->where('full_name', 'like', '%' . $data['search'] . '%');
        }
        if (isset($data['type'])) {
            $users = $users->where('type', $data['type']);
        }
        if (isset($data['status'])) {
            $users = $users->where('status', $data['status']);
        }

        $users = $users->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $users;
    }

    public function DeleteUser($data)
    {
        $user = User::find($data['user_id']);
        return $user->delete();
    }

    public function ChangeUserStatus($data)
    {
        $user = User::find($data['user_id']);
        return $user->update($data);
    }

    public function GetOneUser($data){
        $user = User::with(['BeneficiaryData', 'BeneficiaryAttachment'])->find($data['user_id']);
        return $user;
    }
}

