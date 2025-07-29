<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarMaintenanceHistory;

class CarMaintenanceHistorySeeder extends BaseSeeder
{
    public function run(): void
    {
        CarMaintenanceHistory::query()->truncate();

        $carIds = Car::query()->pluck('id')->all();

        if (empty($carIds)) {
            return;
        }

        $now = $this->now();

        $makes = [
            [
                'name' => 'Periodic maintenance',
                'amount' => rand(100000, 4000000),
                'currency_id' => 1,
                'car_id' => $carIds[rand(0, count($carIds) - 1)],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Replace tire',
                'amount' => rand(100000, 4000000),
                'currency_id' => 1,
                'car_id' => $carIds[rand(0, count($carIds) - 1)],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Hubcap',
                'amount' => rand(100000, 4000000),
                'currency_id' => 1,
                'car_id' => $carIds[rand(0, count($carIds) - 1)],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Modified parking lights',
                'amount' => rand(100000, 4000000),
                'currency_id' => 1,
                'car_id' => $carIds[rand(0, count($carIds) - 1)],
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        CarMaintenanceHistory::query()->insert($makes);
    }
}
