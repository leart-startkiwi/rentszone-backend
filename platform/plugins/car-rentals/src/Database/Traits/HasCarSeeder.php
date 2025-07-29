<?php

namespace Botble\CarRentals\Database\Traits;

use Botble\ACL\Models\User;
use Botble\Base\Facades\MetaBox;
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

trait HasCarSeeder
{
    protected function createCars(array $cars, bool $truncate = true): void
    {
        if ($truncate) {
            Car::query()->truncate();
        }

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

            // Add external booking URL to approximately 1/4 of the cars
            $hasExternalBooking = rand(1, 4) === 1;
            $externalBookingUrl = null;

            if ($hasExternalBooking) {
                $bookingSites = [
                    // Major car rental companies
                    'https://www.hertz.com/rentacar/reservation/vehicles?location=',
                    'https://www.enterprise.com/en/reserve.html?vehicleType=',
                    'https://www.avis.com/en/reserve/vehicles?location=',
                    'https://www.budget.com/en/reservation/vehicles?location=',
                    'https://www.sixt.com/rental-car/usa/vehicle/',
                    'https://www.europcar.com/en-us/car-rental/vehicles/',
                    // Aggregators and online travel agencies
                    'https://www.rentalcars.com/en/search-results/',
                    'https://www.kayak.com/cars/',
                    'https://www.expedia.com/carsearch?vehicle=',
                    'https://www.turo.com/us/en/search?vehicle=',
                ];

                $carSlug = str_replace(' ', '-', strtolower($item['name']));
                $externalBookingUrl = $bookingSites[array_rand($bookingSites)] . $carSlug;
            }

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
                    'external_booking_url' => $externalBookingUrl,
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
