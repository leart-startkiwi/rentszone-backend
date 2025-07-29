<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(BookingStatusEnum::values()),
        ];
    }
}
