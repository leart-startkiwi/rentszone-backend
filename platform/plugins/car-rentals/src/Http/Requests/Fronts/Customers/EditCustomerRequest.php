<?php

namespace Botble\CarRentals\Http\Requests\Fronts\Customers;

use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;

class EditCustomerRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', 'min:2'],
            'phone' => ['nullable', 'string', ...explode('|', BaseHelper::getPhoneValidationRule())],
            'dob' => ['nullable', 'date'],
        ];
    }
}
