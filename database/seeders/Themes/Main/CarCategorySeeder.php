<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarCategory;
use Botble\CarRentals\Models\CarsCategory;

class CarCategorySeeder extends BaseSeeder
{
    public function run(): void
    {
        CarCategory::query()->truncate();
        CarsCategory::query()->truncate();

        $now = $this->now();

        $makeCategories = [
            [
                'name' => 'Sport',
                'parent_id' => 0,
                'description' => 'Sport cars model',
                'icon' => 'ti ti-sport-billard',
                'order' => 1,
                'is_featured' => 0,
                'is_default' => 0,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Maserati',
                'parent_id' => 1,
                'description' => '',
                'icon' => 'ti ti-activity',
                'order' => 3,
                'is_featured' => 0,
                'is_default' => 0,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ferrari',
                'parent_id' => 1,
                'description' => '',
                'icon' => null,
                'order' => 3,
                'is_featured' => 0,
                'is_default' => 0,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Classic',
                'parent_id' => 0,
                'description' => 'Classic cars model',
                'icon' => 'ti ti-alpha',
                'order' => 2,
                'is_featured' => 0,
                'is_default' => 0,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'New',
                'parent_id' => 0,
                'description' => 'New cars model',
                'icon' => 'ti ti-new-section',
                'order' => 4,
                'is_featured' => 0,
                'is_default' => 1,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        CarCategory::query()->insert($makeCategories);

        $categories = CarCategory::query()->pluck('id')->all();
        $carIds = Car::query()->pluck('id')->all();

        if (! empty($carIds)) {
            $makeCarsCategories = [];
            foreach ($carIds as $carId) {
                $makeCarsCategories[] = [
                    'cr_car_category_id' => $categories[rand(0, count($categories) - 1)],
                    'cr_car_id' => $carId,
                ];
            }

            CarsCategory::query()->insert($makeCarsCategories);
        }
    }
}
