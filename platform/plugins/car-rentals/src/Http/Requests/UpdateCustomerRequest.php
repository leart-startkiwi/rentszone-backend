<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:120', 'min:2'],
            'email' => ['required', 'string', 'email', 'unique:cr_customers,email,' . $this->route('customer.id')],
            'phone' => ['nullable', 'string', ...explode('|', BaseHelper::getPhoneValidationRule())],
            'avatar' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
        ];

        if ($this->boolean('is_change_password')) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }
}
