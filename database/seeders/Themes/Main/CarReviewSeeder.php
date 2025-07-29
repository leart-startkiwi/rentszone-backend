<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Customer;

class CarReviewSeeder extends BaseSeeder
{
    public function run(): void
    {
        CarReview::query()->truncate();

        $customerIds = Customer::query()->pluck('id')->all();
        $carIds = Car::query()->pluck('id')->all();

        $fake = $this->fake();

        for ($i = 0; $i < 20; $i++) {
            CarReview::query()->create([
                'star' => $fake->numberBetween(1, 5),
                'customer_id' => $fake->randomElement($customerIds),
                'car_id' => $fake->randomElement($carIds),
                'content' => $fake->text,
                'status' => BaseStatusEnum::PUBLISHED,
            ]);
        }
    }
}
