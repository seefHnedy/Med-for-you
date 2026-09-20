<?php

namespace App\Http\Requests\Pharmacy;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PharmacyIdRequest extends BaseRequest
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
        ];
    }
}
