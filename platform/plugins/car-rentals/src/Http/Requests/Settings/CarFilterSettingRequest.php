<?php

namespace Botble\CarRentals\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CarFilterSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'enabled_car_filter' => new OnOffRule(),
            'filter_cars_by' => ['nullable', 'array'],
            'filter_cars_by.*' => ['required', Rule::in(CarRentalsHelper::getCarsFilterKeys())],
        ];
    }
}
