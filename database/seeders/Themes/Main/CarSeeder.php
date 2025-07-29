<?php

namespace Database\Seeders\Themes\Main;

use Botble\ACL\Models\User;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Models\CarColor;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarType;
use Botble\CarRentals\Models\Customer;
use Botble\Faq\Models\Faq;

class CarSeeder extends BaseSeeder
{
    public function run(): void
    {
        Car::query()->truncate();

        $this->uploadFiles('cars');

        $cars = [
            [
                'license_plate' => '30A-123.00',
                'name' => 'Toyota Camry XLE Hybrid 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.11',
                'name' => 'Honda Accord Sport 2.0T 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '29A-123.22',
                'name' => 'Mercedes-Benz C300 4MATIC 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.33',
                'name' => 'BMW 330i xDrive M Sport 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.44',
                'name' => 'Lexus ES 350 F Sport 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.55',
                'name' => 'Toyota RAV4 Prime XSE AWD 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.66',
                'name' => 'Honda CR-V Touring Hybrid AWD 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.77',
                'name' => 'BMW X5 xDrive40i M Sport 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '30A-123.88',
                'name' => 'Mercedes-Benz GLC 300 4MATIC 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
            [
                'license_plate' => '29A-123.99',
                'name' => 'Lexus RX 350 F Sport Handling AWD 2024',
                'insurance_info' => '',
                'current_location' => '',
            ],
        ];

        $addressIds = CarAddress::query()->pluck('id')->all();
        $makeIds = CarMake::query()->pluck('id')->all();
        $vehicleTypeIds = CarType::query()->pluck('id')->all();
        $fuelTypeIds = CarFuel::query()->pluck('id')->all();
        $transmissionTypeIds = CarFuel::query()->pluck('id')->all();
        $colorIds = CarColor::query()->pluck('id')->all();
        $vendorIds = Customer::query()->where('is_vendor', 1)->pluck('id')->all();
        $faqIds = is_plugin_active('faq') ? Faq::query()->pluck('id') : collect();

        foreach ($cars as $key => $item) {
            $images = [];

            for ($i = 1; $i <= 2; $i++) {
                $images[] = $this->filePath(sprintf('cars/%s.jpg', rand(1, 10)));
            }

            for ($i = 1; $i <= 4; $i++) {
                $images[] = $this->filePath(sprintf('cars/car-interiors-%s.jpg', rand(1, 8)));
            }

            $addressId = $addressIds[rand(0, count($addressIds) - 1)];

            /**
             * @var Car $car
             */
            $car = Car::query()->forceCreate(
                [
                    ...$item,
                    'number_of_seats' => [4, 5, 7, 8][rand(0, 3)],
                    'number_of_doors' => [2, 4, 5][rand(0, 2)],
                    'is_featured' => $key % 2 == 0 ? 1 : 0,
                    'is_used' => rand(0, 3) === 0 ? 1 : 0,
                    'make_id' => $makeIds[rand(0, count($makeIds) - 1)],
                    'vin' => $this->generateVin(),
                    'mileage' => rand(1000, 20000),
                    'rental_rate' => rand(30, 99),
                    'content' => file_get_contents(database_path('seeders/contents/car.html')),
                    'status' => CarStatusEnum::AVAILABLE,
                    'images' => $images,
                    'pick_address_id' => $addressId,
                    'return_address_id' => $addressId,
                    'year' => rand(2010, 2024),
                    'vehicle_type_id' => $vehicleTypeIds[rand(0, count($vehicleTypeIds) - 1)],
                    'fuel_type_id' => $fuelTypeIds[rand(0, count($fuelTypeIds) - 1)],
                    'transmission_id' => $transmissionTypeIds[rand(0, count($transmissionTypeIds) - 1)],
                    'moderation_status' => ModerationStatusEnum::APPROVED,
                    'author_id' => $key % 2 == 0 ? $vendorIds[rand(0, count($vendorIds) - 1)] : 1,
                    'author_type' => $key % 2 == 0 ? Customer::class : User::class,
                ],
            );

            $car->colors()->sync([$colorIds[rand(0, count($colorIds) - 1)]]);

            if ($faqIds->isNotEmpty()) {
                MetaBox::saveMetaBoxData(
                    $car,
                    'faq_ids',
                    $faqIds->random($faqIds->count() >= 5 ? 5 : 1)->all()
                );
            }
        }
    }

    public function generateVin(): string
    {
        $allowedChars = '0123456789ABCDEFGHJKLMNPRSTUVWXYZ';

        $vin = '';

        for ($i = 0; $i < 3; $i++) {
            $vin .= $allowedChars[rand(0, strlen($allowedChars) - 1)];
        }

        for ($i = 0; $i < 5; $i++) {
            $vin .= $allowedChars[rand(0, strlen($allowedChars) - 1)];
        }

        $vin .= rand(0, 9);

        for ($i = 0; $i < 8; $i++) {
            $vin .= $allowedChars[rand(0, strlen($allowedChars) - 1)];
        }

        return $vin;
    }
}
