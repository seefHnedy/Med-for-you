<?php

namespace App\Http\Requests\Donation;

use App\Enums\AccountStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\BaseRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyDonationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => \auth('User')->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
                    ->where('status', AccountStatusEnum::APPROVED)
                    ->where('type', UserTypeEnum::DONOR)
            ],
            'donation_id' => [
                'required',
                Rule::exists('donations', 'id')
                    ->where('user_id', $this->input('user_id'))
                    ->where(function ($query) {
                        $query->whereNull('otp_expire_at')
                            ->orWhere('otp_expire_at', '>', Carbon::now());
                    })
            ],
            'otp' => 'required|regex:/^\d{6}$/',
            ];
    }
}
