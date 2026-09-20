<?php

namespace App\Http\Requests\Pharmacy;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPharmacyRequest extends BaseRequest
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
            'name' => [Rule::unique('pharmacies')->whereNull('deleted_at'), 'required', 'string'],
            'email' => [Rule::unique('pharmacies')->whereNull('deleted_at'), 'required', 'email'],
            'password' => 'required|min:8|confirmed',
            'phone' => [
                Rule::unique('pharmacies')->whereNull('deleted_at'),
                'required',
                'regex:/^0(11|12|21|31|33|41|43|51|52|53|54|55|56|57)[0-9]{7}$/',
            ],
            'city_id' => [Rule::exists('cities', 'id'), 'required'],
            'location' => 'required|string',
            'location_link' => [
                'nullable',
                'url',
                'required_without_all:location_longitude,location_latitude',
            ],
            'location_longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
                'required_without_all:location_link',
                'required_with:location_latitude',
            ],
            'location_latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
                'required_without_all:location_link',
                'required_with:location_longitude',
            ],
            'owner_name' => 'required|string',
            'owner_phone' => [Rule::unique('pharmacies')->whereNull('deleted_at'), 'required', 'regex:/^09[0-9]{8}$/'],
            'license' => 'required|image',
            'license_expire_at' => 'required|date|after:' . \now(),


        ];
    }
}
