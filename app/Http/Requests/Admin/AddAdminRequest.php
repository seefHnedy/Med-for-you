<?php

namespace App\Http\Requests\Admin;

use App\Enums\AdminTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddAdminRequest extends BaseRequest
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
            'name' => 'required',
            'email' => [Rule::unique('admins')->whereNull('deleted_at'), 'required', 'email'],
            'password' => 'required|min:8|confirmed',
            'type' => [Rule::in(AdminTypeEnum::toArray()), 'required'],
        ];
    }
}
