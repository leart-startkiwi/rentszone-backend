<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\CarAmenity;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Models\CarTransmission;
use Botble\CarRentals\Models\CarType;

class CarAttributeSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->runCarType();
        $this->runCarTransmission();
        $this->runCarFuel();
        $this->runCarAmenity();
    }

    public function runCarType(): void
    {
        CarType::query()->truncate();

        $data = [
            [
                'name' => 'SUV',
            ],
            [
                'name' => 'Hatchback',
            ],
            [
                'name' => 'Sedan',
            ],
            [
                'name' => 'Crossover',
            ],
            [
                'name' => 'Minivan',
            ],
            [
                'name' => 'Coupe',
            ],
            [
                'name' => 'Sport Cars',
            ],
            [
                'name' => 'Pickup Truck',
            ],
        ];

        foreach ($data as $index => $item) {
            CarType::query()->create([
                ...$item,
                'image' => $this->filePath(sprintf('cars/car-%d.jpg', $index + 1)),
                'status' => BaseStatusEnum::PUBLISHED,
            ]);
        }
    }

    public function runCarTransmission(): void
    {
        CarTransmission::query()->truncate();

        $data = [
            [
                'name' => 'Automatic',
                'icon' => $this->filePath('icons/car-transmission-auto.png'),
            ],
            [
                'name' => 'Manual',
                'icon' => $this->filePath('icons/car-transmission-manual.png'),
            ],
        ];

        foreach ($data as $item) {
            CarTransmission::query()->create([...$item, 'status' => BaseStatusEnum::PUBLISHED]);
        }
    }

    public function runCarFuel(): void
    {
        CarFuel::query()->truncate();

        $data = [
            [
                'name' => 'Gasoline',
                'icon' => $this->filePath('icons/car-diesel.png'),
            ],
            [
                'name' => 'Diesel',
                'icon' => $this->filePath('icons/car-diesel.png'),
            ],
            [
                'name' => 'Electric',
                'icon' => $this->filePath('icons/car-electricity.png'),
            ],
        ];

        foreach ($data as $item) {
            CarFuel::query()->create([...$item, 'status' => BaseStatusEnum::PUBLISHED]);
        }
    }

    public function runCarAmenity(): void
    {
        CarAmenity::query()->truncate();

        $data = [
            [
                'name' => 'Leather upholstery',
            ],
            [
                'name' => 'Heated seats',
            ],
            [
                'name' => 'Sunroof/Moonroof',
            ],
            [
                'name' => 'Heads-up display',
            ],
            [
                'name' => 'Adaptive cruise control',
            ],
        ];

        foreach ($data as $item) {
            CarAmenity::query()->create([...$item, 'status' => BaseStatusEnum::PUBLISHED]);
        }
    }
}
