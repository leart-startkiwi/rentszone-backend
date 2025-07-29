<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Service;
use Botble\Shortcode\Facades\Shortcode;
use Illuminate\Support\Facades\File;

class CarServiceSeeder extends BaseSeeder
{
    public function run(): void
    {
        Service::query()->truncate();

        $faker = $this->fake();
        $services = [
            [
                'name' => 'Driver Rental Service',
                'description' => 'In addition to our car rental service, we offer professional drivers for hire. Enjoy a stress-free journey with our experienced drivers handling the road.',
                'logo' => $this->filePath('icons/lexus.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Oil Change Service',
                'description' => 'Keep your engine in top condition with our oil change service. Available as an add-on to any of our maintenance or repair services.',
                'logo' => $this->filePath('icons/mer.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Car Wash & Detailing Package',
                'description' => 'Enhance your car rental or repair experience with our premium car wash and detailing service, leaving your car spotless inside and out.',
                'logo' => $this->filePath('icons/bugatti.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Roadside Assistance',
                'description' => 'Our roadside assistance service ensures peace of mind while you rent or drive. Get help with breakdowns, flat tires, or towing when you need it most.',
                'logo' => $this->filePath('icons/jaguar.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Temporary Car Replacement',
                'description' => 'If your car is in for repairs or maintenance, we offer a temporary car replacement service so you’re never without transportation.',
                'logo' => $this->filePath('icons/honda.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Tire Replacement & Balancing',
                'description' => 'We provide tire replacement and wheel balancing services, ensuring your car is safe and smooth on the road, available as an add-on to any maintenance package.',
                'logo' => $this->filePath('icons/chevrolet.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Vehicle Inspection Service',
                'description' => 'For those looking to sell or rent a car, we offer thorough vehicle inspection services to certify your car’s condition and increase its market value.',
                'logo' => $this->filePath('icons/chevrolet.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Car Insurance Assistance',
                'description' => 'Our experts can help you find the right car insurance policy, available as an add-on when purchasing or renting a vehicle from us.',
                'logo' => $this->filePath('icons/chevrolet.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Pick-Up & Drop-Off Service',
                'description' => 'We offer a convenient pick-up and drop-off service when you rent a car, have your car serviced, or use our detailing services.',
                'logo' => $this->filePath('icons/chevrolet.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Premium Fuel Service',
                'description' => 'Refuel your rental or serviced vehicle with high-quality premium fuel before hitting the road, ensuring optimal performance and mileage.',
                'logo' => $this->filePath('icons/chevrolet.png'),
                'price' => $faker->numberBetween(100, 1000),
            ],
        ];

        $content = File::get(database_path('seeders/contents/post.html'));

        foreach ($services as $index => $service) {
            Service::query()->create([
                ...$service,
                'content' => str_replace([
                    '[content-images]',
                    '[content-columns]',
                ], [
                    Shortcode::generateShortcode('content-images', [
                        'quantity' => 2,
                        'image_1' => $this->filePath('news/' . (rand(1, 10)) . '.jpg'),
                        'image_2' => $this->filePath('news/' . (rand(1, 10)) . '.jpg'),
                    ]),
                    Shortcode::generateShortcode('content-columns', [
                        'quantity' => 2,
                        'content_1' => $faker->text(400),
                        'content_2' => $faker->text(400),
                    ]),
                ], $content),
                'image' => $this->filePath('news/' . ($index + 1) . '.jpg'),
                'status' => BaseStatusEnum::PUBLISHED,
            ]);
        }
    }
}
