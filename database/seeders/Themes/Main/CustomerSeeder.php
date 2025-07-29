<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('customers');

        Customer::query()->truncate();

        $faker = $this->fake();

        for ($i = 0; $i < 10; $i++) {
            $customers[] = [
                'name' => $faker->name(),
                'email' => $faker->safeEmail(),
                'phone' => $faker->e164PhoneNumber(),
                'password' => Hash::make('12345678'),
                'avatar' => sprintf('customers/%d.jpg', $i + 1),
            ];
        }

        $randNumber = rand(1, 10);

        $customers[] = [
            'name' => $faker->name(),
            'email' => 'customer@botble.com',
            'phone' => $faker->e164PhoneNumber(),
            'password' => Hash::make('12345678'),
            'avatar' => sprintf('customers/%d.jpg', $randNumber),
        ];

        $customers[] = [
            'name' => $faker->name(),
            'email' => 'vendor@botble.com',
            'phone' => $faker->e164PhoneNumber(),
            'password' => Hash::make('12345678'),
            'avatar' => sprintf('customers/%d.jpg', $randNumber),
            'is_vendor' => 1,
        ];

        foreach ($customers as $customer) {
            Customer::query()->forceCreate($customer);
        }
    }
}
