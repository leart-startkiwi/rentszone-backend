<?php

namespace Botble\CarRentals\Http\Requests\Fronts;

use Botble\Support\Http\Requests\Request;

class ReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'star' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:5000'],
            'customer_id' => ['required', 'exists:cr_customers,id'],
            'car_id' => ['required', 'exists:cr_cars,id'],
            'booking_id' => ['nullable', 'exists:cr_bookings,id'],
        ];
    }
}
