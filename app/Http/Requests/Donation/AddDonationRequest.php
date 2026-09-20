<?php

namespace App\Http\Requests\Donation;

use App\Enums\AccountStatusEnum;
use App\Enums\DonationTypeEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\BaseRequest;
use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddDonationRequest extends BaseRequest
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
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
                    ->where('status', AccountStatusEnum::APPROVED)
                    ->where('type', UserTypeEnum::DONOR)
            ],
            'medicine_id' => [
                'required',
                Rule::exists('medicines', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')
                        ->where('order_qty', '>', 0);
                })
            ],
            'type' => [
                'required',
                Rule::in(DonationTypeEnum::toArray())
            ],
            'qty' => [
                'required_if:type,' . DonationTypeEnum::PART,
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $medicineId = $this->input('medicine_id');

                    if (!$medicineId) {
                        return;
                    }

                    $medicine = Medicine::find($medicineId);

                    if (!$medicine) {
                        $fail('The selected medicine is invalid.');
                        return;
                    }

                    if ($value > $medicine->order_qty) {
                        $fail("The quantity cannot exceed the available order of {$medicine->order_qty}.");
                    }
                }
            ]
        ];
    }
}
