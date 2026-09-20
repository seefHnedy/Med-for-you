<?php

namespace App\Http\Requests\Pharmacy;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePharmacyRequest extends BaseRequest
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
        if ($this->route('pharmacy_id')) {
            $this->merge([
                'pharmacy_id' => $this->route('pharmacy_id'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pharmacy_id' => [Rule::exists('pharmacies', 'id')->whereNull('deleted_at'), 'required'],
            'email' => [Rule::unique('pharmacies')->whereNull('deleted_at')->ignore($this->pharmacy_id), 'required', 'email'],
            'password' => 'nullable|min:8|confirmed',
            'phone' => [
                Rule::unique('pharmacies')->whereNull('deleted_at')->ignore($this->pharmacy_id),
                'required',
                'regex:/^0(11|12|21|31|33|41|43|51|52|53|54|55|56|57)[0-9]{7}$/',
            ],
            'owner_name' => 'required|string',
            'owner_phone' => [Rule::unique('pharmacies')->whereNull('deleted_at')->ignore($this->pharmacy_id), 'required', 'regex:/^09[0-9]{8}$/'],
        ];
    }
}
