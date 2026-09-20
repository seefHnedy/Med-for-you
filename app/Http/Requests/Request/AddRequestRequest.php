<?php

namespace App\Http\Requests\Request;

use App\Enums\AccountStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRequestRequest extends BaseRequest
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
            'user_id' => [Rule::exists('users', 'id')->whereNull('deleted_at')->where('status', AccountStatusEnum::APPROVED)->where('type', UserTypeEnum::BENEFICIARY), 'required'],
            'disease_name' => 'required|string',
            'doctor_name' => 'required|string',
            'visit_date' => 'required|date|before:' . now(),
            'recipe' => 'required|image',
        ];
    }


}
