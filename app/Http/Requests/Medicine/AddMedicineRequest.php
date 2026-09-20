<?php

namespace App\Http\Requests\Medicine;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddMedicineRequest extends BaseRequest
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
            'name_ar' => [Rule::unique('medicines')->whereNull('deleted_at'), 'required'],
            'name_en' => [Rule::unique('medicines')->whereNull('deleted_at'), 'required'],
            'description' => 'required',
            'price' => 'required|decimal:0,2',
            'factory' => 'required',
            'composition' => 'required',
            'concentration' => 'required',
            'pharmaceutical_form' => 'required',
            'package' => 'required',
        ];
    }
}
