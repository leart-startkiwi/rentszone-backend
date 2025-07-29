<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\CarColor;

class CarColorSeeder extends BaseSeeder
{
    public function run(): void
    {
        CarColor::query()->truncate();

        $now = $this->now();

        $makes = [
            [
                'name' => 'Red',
                'created_at' => $now,
                'updated_at' => $now,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            [
                'name' => 'White',
                'created_at' => $now,
                'updated_at' => $now,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            [
                'name' => 'Black',
                'created_at' => $now,
                'updated_at' => $now,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            [
                'name' => 'Blue',
                'created_at' => $now,
                'updated_at' => $now,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            [
                'name' => 'Pink',
                'created_at' => $now,
                'updated_at' => $now,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            [
                'name' => 'Brown',
                'created_at' => $now,
                'updated_at' => $now,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
        ];

        CarColor::query()->insert($makes);
    }
}
