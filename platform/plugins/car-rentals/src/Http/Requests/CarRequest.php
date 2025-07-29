<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\CarRentals\Enums\CarConditionEnum;
use Botble\CarRentals\Enums\CarForSaleStatusEnum;
use Botble\CarRentals\Enums\CarRentalTypeEnum;
use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Models\CarCategory;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CarRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'rental_rate' => $this->formatPriceValue($this->input('rental_rate')),
            'sale_price' => $this->formatPriceValue($this->input('sale_price')),
            'is_for_sale' => $this->input('car_purpose') === 'sale',
        ]);
    }

    protected function formatPriceValue(string|float|null $number): float
    {
        if (! $number) {
            return 0;
        }

        $decimalSeparator = get_car_rentals_setting('decimal_separator', '.');

        if ($decimalSeparator == 'space') {
            $decimalSeparator = ' ';
        }

        $thousandSeparator = get_car_rentals_setting('thousands_separator', ',');

        if ($thousandSeparator == 'space') {
            $thousandSeparator = ' ';
        }

        $number = str_replace($thousandSeparator, '', $number);

        $number = str_replace($decimalSeparator, '.', $number);

        return (float) $number;
    }

    public function rules(): array
    {
        $isForSale = $this->input('car_purpose') === 'sale';
        $isForRent = $this->input('car_purpose') === 'rent';

        return [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['nullable', 'max:1000'],
            'content' => ['nullable', 'string', 'max:300000'],
            'location' => ['nullable', 'string', 'max:255'],
            'make_id' => ['nullable', 'int'],
            'vehicle_type_id' => ['nullable', 'int'],
            'transmission_id' => ['nullable', 'int'],
            'fuel_type_id' => ['nullable', 'int'],
            'year' => ['nullable', 'int', 'min:1900', 'max:3000'],
            'number_of_seats' => ['nullable', 'int', 'min:0', 'max:10000'],
            'number_of_doors' => ['nullable', 'int', 'min:0', 'max:10000'],
            'car_purpose' => ['nullable', 'string', Rule::in(['rent', 'sale'])],
            'rental_rate' => [
                'numeric',
                'min:0',
                'max:1000000000',
                Rule::requiredIf(fn () => $isForRent),
            ],
            'rental_type' => [
                'string',
                Rule::in(CarRentalTypeEnum::values()),
                Rule::requiredIf(fn () => $isForRent),
            ],
            'tax_id' => [
                'nullable',
                'exists:cr_taxes,id',
            ],
            'license_plate' => ['nullable', 'string', 'max:1000'],
            'vin' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:1000'],
            'status' => ['required', 'string', Rule::in(CarStatusEnum::values())],
            'pick_address_id' => ['required', 'int'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['sometimes', Rule::exists((new CarCategory())->getTable(), 'id')],
            'is_same_drop_off' => ['boolean'],
            'return_address_id' => ['int', Rule::requiredIf(fn () => ! $this->is_same_drop_off)],
            'is_featured' => ['nullable', 'boolean'],
            'colors' => ['nullable', 'string', 'max:1000'],
            'is_used' => new OnOffRule(),
            'is_for_sale' => ['sometimes', 'boolean'],
            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:1000000000',
                Rule::requiredIf(fn () => $isForSale),
            ],
            'condition' => [
                'nullable',
                'string',
                Rule::in(CarConditionEnum::values()),
                Rule::requiredIf(fn () => $isForSale),
            ],
            'ownership_history' => ['nullable', 'string', 'max:1000'],
            'insurance_info' => ['nullable', 'string', 'max:5000'],
            'warranty_information' => ['nullable', 'string', 'max:5000'],
            'sale_status' => [
                'nullable',
                'string',
                Rule::in(CarForSaleStatusEnum::values()),
                Rule::requiredIf(fn () => $isForSale),
            ],
            'external_booking_url' => [
                'nullable',
                'url',
                'max:2000',
            ],
        ];
    }
}
