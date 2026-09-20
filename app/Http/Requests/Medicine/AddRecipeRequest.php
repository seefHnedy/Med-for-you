<?php

namespace App\Http\Requests\Medicine;

use App\Enums\AccountStatusEnum;
use App\Enums\RecipePriorityEnum;
use App\Enums\RequestStatusEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRecipeRequest extends BaseRequest
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
            'request_id' => [Rule::exists('requests','id')->where('status',RequestStatusEnum::PENDING),'required'],
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => [Rule::exists('medicines','id')->whereNull('deleted_at'),'required','distinct'],
            'medicines.*.qty' => 'required|integer|min:1',
            'priority' => [Rule::in(RecipePriorityEnum::toArray()),'required'],
        ];
    }
}
