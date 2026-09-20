<?php

namespace App\Http\Requests\User;

use App\Enums\UserTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends BaseRequest
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
            'full_name' => 'required',
            'birthday' => 'required|date|before:' . now()->subYears(18)->toDateString(),
            'city_id' => [Rule::exists('cities', 'id'), 'required'],
            'phone' => [Rule::unique('users')->whereNull('deleted_at'), 'required', 'regex:/^09[0-9]{8}$/'],
            'email' => [Rule::unique('users')->whereNull('deleted_at'), 'required', 'email'],
            'password' => 'required|min:8|confirmed',
            'type' => [Rule::in(UserTypeEnum::toArray()), 'required'],
            'father_name' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'mother_name' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'national_number' => 'regex:/^[0-9]{11}$/|required_if:type,' . UserTypeEnum::BENEFICIARY,
            'work' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'current_address' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'permanent_address' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'health_status' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'disease_name' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'disability_status' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'family_income' => 'required_if:type,' . UserTypeEnum::BENEFICIARY,
            'personal_image' => 'image|required_if:type,'. UserTypeEnum::BENEFICIARY,
            'id_front_image' =>  'image|required_if:type,'. UserTypeEnum::BENEFICIARY,
            'id_back_image' =>  'image|required_if:type,'. UserTypeEnum::BENEFICIARY,
            'salary_statement_image' =>  'image|required_if:type,'. UserTypeEnum::BENEFICIARY,
            'medical_report_image' =>  'image|required_if:type,'. UserTypeEnum::BENEFICIARY,
        ];
    }
}
