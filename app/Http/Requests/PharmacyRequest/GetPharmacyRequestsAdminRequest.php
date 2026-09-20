<?php

namespace App\Http\Requests\PharmacyRequest;

use App\Enums\PharmacyRequestStatusEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetPharmacyRequestsAdminRequest extends BaseRequest
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
            'pharmacy_id' => [
                'required',
                Rule::exists('pharmacies', 'id')
                    ->whereNull('deleted_at')
            ],
            'status' => [Rule::in(PharmacyRequestStatusEnum::toArray()), 'nullable'],
        ];
    }
}
