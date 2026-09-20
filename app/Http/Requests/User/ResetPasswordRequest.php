<?php

namespace App\Http\Requests\User;

use App\Enums\AccountStatusEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends BaseRequest
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
        return [
            'email' => [Rule::exists('users','email')->whereNull('deleted_at')->where('status',AccountStatusEnum::APPROVED),'required','email'],
            'otp' => 'required|regex:/^\d{6}$/',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
