<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Currency;

class CurrencySeeder extends BaseSeeder
{
    public function run(): void
    {
        Currency::query()->truncate();

        $currencies = [
            [
                'title' => 'USD',
                'symbol' => '$',
                'is_prefix_symbol' => true,
                'order' => 0,
                'decimals' => 0,
                'is_default' => 1,
                'exchange_rate' => 1,
            ],
            [
                'title' => 'EUR',
                'symbol' => '€',
                'is_prefix_symbol' => false,
                'order' => 1,
                'decimals' => 0,
                'is_default' => 0,
                'exchange_rate' => 0.84,
            ],
            [
                'title' => 'VND',
                'symbol' => '₫',
                'is_prefix_symbol' => false,
                'order' => 2,
                'decimals' => 0,
                'is_default' => 0,
                'exchange_rate' => 23203,
            ],
            [
                'title' => 'NGN',
                'symbol' => '₦',
                'is_prefix_symbol' => true,
                'order' => 2,
                'decimals' => 0,
                'is_default' => 0,
                'exchange_rate' => 895.52,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::query()->create($currency);
        }
    }
}
