<?php

namespace App\Http\Requests\User;

use App\Enums\AccountStatusEnum;
use App\Enums\OTPTypeEnum;
use App\Http\Requests\BaseRequest;
use Faker\Provider\Base;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResendOTPRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $arr = [
            'otp_type' => [Rule::in(OTPTypeEnum::toArray()),'required'],
        ];

        if ($this->input('otp_type')  === OTPTypeEnum::REGISTER){
            $arr['email'] = [Rule::exists('users','email')->whereNull('deleted_at')->where('status',AccountStatusEnum::OTP),'required','email'];
        }else{
            $arr['email'] = [Rule::exists('users','email')->whereNull('deleted_at')->where('status',AccountStatusEnum::APPROVED),'required','email'];
        }

        return $arr;
    }
}
