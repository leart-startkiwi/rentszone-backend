<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\CarAddress;
use Botble\Location\Models\City;

class CarAddressSeeder extends BaseSeeder
{
    public function run(): void
    {
        CarAddress::query()->truncate();

        $now = $this->now();
        $fake = $this->fake();

        $cities = City::query()
            ->with(['state', 'country'])
            ->get();

        $data = [];

        for ($i = 0; $i <= 10; $i++) {
            $city = $cities->random();

            $data[] = [
                'detail_address' => $fake->streetAddress(),
                'status' => BaseStatusEnum::PUBLISHED,
                'city_id' => $city->id,
                'state_id' => $city->state->id,
                'country_id' => $city->country->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        CarAddress::query()->insert($data);
    }
}
