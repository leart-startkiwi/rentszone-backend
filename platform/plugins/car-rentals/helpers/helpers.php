<?php

use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Models\Car;
use Illuminate\Support\Facades\Cache;

if (! function_exists('get_car_rentals_setting')) {
    function get_car_rentals_setting($key, $default = null)
    {
        return setting('car_rentals_' . $key, $default);
    }
}

if (! function_exists('generate_car_rental_price_ranges')) {
    function generate_car_rental_price_ranges(int $step = 1000): array
    {
        $min = 0;
        $max = Car::query()->where('status', CarStatusEnum::AVAILABLE)->max('rental_rate') ?? 0;

        if ($max <= 0) {
            return [];
        }

        $currency = get_application_currency();
        $currencyTitle = $currency ? $currency->title : 'default';

        $cacheKey = 'car_rental_price_ranges_' . $step . '_' . $currencyTitle;

        $ranges = Cache::get($cacheKey, []);

        if (! $ranges) {
            for ($i = $min; $i < $max; $i += $step) {
                $upper = min($i + $step, $max);
                $ranges[$i . '_' . $upper] = format_price($i) . ' - ' . format_price($upper);
            }

            Cache::put($cacheKey, $ranges, 60 * 60);
        }

        return $ranges;
    }
}
