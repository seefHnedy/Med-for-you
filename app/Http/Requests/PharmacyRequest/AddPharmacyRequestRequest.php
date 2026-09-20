<?php

namespace App\Http\Requests\PharmacyRequest;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPharmacyRequestRequest extends BaseRequest
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
            'recipe_id' => ['required', Rule::exists('recipes', 'id')],
            'recipe_medicines' => ['required', 'array'],
            'recipe_medicines.*' => [
                'required',
                'distinct',
                Rule::exists('recipe_medicines', 'id')->where('recipe_id',$this->input('recipe_id'))->whereNull('pharmacy_id')
            ]
        ];
    }
}
