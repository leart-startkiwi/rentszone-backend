<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Support\Http\Requests\Request;

class CarMaintenanceHistoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'amount' => ['required', 'min:1'],
            'car_id' => ['required', 'exists:cr_cars,id'],
            'date' => ['nullable', 'date'],
        ];
    }
}
