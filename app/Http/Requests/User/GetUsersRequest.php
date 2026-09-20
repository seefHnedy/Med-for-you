<?php

namespace App\Http\Requests\User;

use App\Enums\AccountStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetUsersRequest extends BaseRequest
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
            'type' => [Rule::in(UserTypeEnum::toArray()),'nullable'],
            'status' => [Rule::in(AccountStatusEnum::toArray()),'nullable'],
            'search' => 'nullable'
        ];
    }
}
