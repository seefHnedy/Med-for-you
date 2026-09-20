<?php

namespace App\Http\Requests\PharmacyRequest;

use App\Http\Requests\BaseRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyPharmacyRequestRequest extends BaseRequest
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
            'pharmacy_id' => \auth('Pharmacy')->user()->id,
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
            'pharmacy_id' => [
                'required',
                Rule::exists('pharmacies', 'id')
                    ->whereNull('deleted_at')
            ],
            'pharmacy_request_id' => [
                'required',
                Rule::exists('pharmacy_requests', 'id')
                    ->where('pharmacy_id', $this->input('pharmacy_id'))
                    ->where(function ($query) {
                        $query->whereNull('otp_expire_at')
                            ->orWhere('otp_expire_at', '>', Carbon::now());
                    })
            ],
            'otp' => 'required|regex:/^\d{6}$/',
            ];
    }
}
