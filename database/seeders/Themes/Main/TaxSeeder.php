<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Tax;

class TaxSeeder extends BaseSeeder
{
    public function run(): void
    {
        Tax::query()->truncate();

        $taxes = [
            'Import Duty',
            'Value Added Tax (VAT)',
            'Currency Conversion',
            'Brokerage',
            'Storage',
            'Administrative',
            'Handling',
            'Insurance',
            'Rural Delivery',
            'Return Shipping',
            'Environmental',
            'Excise',
        ];

        $faker = $this->fake();

        foreach ($taxes as $index => $taxName) {
            Tax::query()->create([
                'name' => $taxName,
                'percentage' => $faker->randomFloat(2, 0.1, 5),
                'status' => BaseStatusEnum::PUBLISHED,
                'priority' => $index + 1,
            ]);
        }
    }
}
