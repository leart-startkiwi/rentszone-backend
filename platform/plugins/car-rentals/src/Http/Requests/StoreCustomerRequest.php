<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', 'min:2'],
            'email' => ['required', 'string', 'email', 'unique:cr_customers,email'],
            'phone' => ['nullable', 'string', ...explode('|', BaseHelper::getPhoneValidationRule())],
            'avatar' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
