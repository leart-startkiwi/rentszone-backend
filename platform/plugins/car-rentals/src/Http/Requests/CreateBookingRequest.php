<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateBookingRequest extends Request
{
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'exists:cr_cars,id'],
            'rental_start_date' => ['required', 'date', 'after_or_equal:today'],
            'rental_end_date' => ['required', 'date', 'after_or_equal:rental_start_date'],
            'customer_age' => ['nullable', 'integer', 'min:18'],
            'address' => ['nullable', 'string', 'max:400'],
            'city' => ['nullable', 'string', 'max:60'],
            'state' => ['nullable', 'string', 'max:60'],
            'country' => ['nullable', 'string', 'max:60'],
            'zip' => ['nullable', 'string', 'max:10'],
            'note' => ['nullable', 'string', 'max:10000'],
            'services' => ['nullable', 'array'],
            'transaction_id' => ['nullable', 'string', 'max:60'],
        ];
    }
}