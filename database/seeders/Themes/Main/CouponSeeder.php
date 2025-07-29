<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Enums\CouponTypeEnum;
use Botble\CarRentals\Models\Coupon;
use Illuminate\Support\Str;

class CouponSeeder extends BaseSeeder
{
    public function run(): void
    {
        Coupon::query()->truncate();
        $types = [CouponTypeEnum::MONEY, CouponTypeEnum::PERCENTAGE];

        $faker = $this->fake();
        $now = $this->now();

        foreach (range(10, 40) as $ignored) {
            $type = $types[$this->random(0, 1)];
            $value = match ($type) {
                CouponTypeEnum::PERCENTAGE => $faker->randomFloat(2, 0.01, 99.99),
                CouponTypeEnum::MONEY => $faker->randomDigit(),
            };

            $isUnlimited = rand(0, 1);
            $isUnlimitedExpires = rand(0, 1);

            Coupon::query()->create([
                'code' => Str::random(12),
                'type' => $type,
                'value' => $value,
                'is_unlimited_expires' => $isUnlimitedExpires,
                'expires_at' => $isUnlimitedExpires ? null : $now->addMonth(),
                'is_unlimited' => $isUnlimited,
                'limit' => $isUnlimited ? 0 : $this->random(100, 1000),
                'used' => $this->random(10, 130),
            ]);
        }
    }
}
