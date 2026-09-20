<?php

namespace App\Http\Requests\Admin;

use App\Enums\AdminTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends BaseRequest
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
        if ($this->route('admin_id')) {
            $this->merge([
                'admin_id' => $this->route('admin_id'),
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
            'admin_id' => [Rule::exists('admins', 'id')->whereNull('deleted_at'), 'required'],
            'name' => 'required',
            'email' => [Rule::unique('admins')->whereNull('deleted_at')->ignore($this->admin_id), 'required', 'email'],
            'password' => 'nullable|min:8|confirmed',
            'type' => [Rule::in(AdminTypeEnum::toArray()), 'required'],
        ];
    }
}
