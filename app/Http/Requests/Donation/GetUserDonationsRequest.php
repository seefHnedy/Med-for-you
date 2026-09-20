<?php

namespace App\Http\Requests\Donation;

use App\Enums\DonationStatusEnum;
use App\Http\Requests\BaseRequest;
use Faker\Provider\Base;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetUserDonationsRequest extends BaseRequest
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
        if ($this->route('user_id')) {
            $this->merge([
                'user_id' => $this->route('user_id'),
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
            'user_id' => [Rule::exists('users', 'id')->whereNull('deleted_at'), 'required'],
            'status' => [Rule::in(DonationStatusEnum::toArray()), 'nullable'],
            ];
    }
}
