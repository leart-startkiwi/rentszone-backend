<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\CarMake;

class CarMakeSeeder extends BaseSeeder
{
    public function run(): void
    {
        CarMake::query()->truncate();

        $makes = [
            [
                'name' => 'Lexus',
                'logo' => $this->filePath('icons/lexus.png'),
                'logo_dark' => $this->filePath('icons/lexus-w.png'),
                'logo_invert' => $this->filePath('icons/lexus-w.png'),
            ],
            [
                'name' => 'Mercedes',
                'logo' => $this->filePath('icons/mer.png'),
                'logo_dark' => $this->filePath('icons/mer-w.png'),
                'logo_invert' => $this->filePath('icons/mer-i.png'),
            ],
            [
                'name' => 'Bugatti',
                'logo' => $this->filePath('icons/bugatti.png'),
                'logo_dark' => $this->filePath('icons/bugatti-w.png'),
                'logo_invert' => $this->filePath('icons/bugatti-w.png'),
            ],
            [
                'name' => 'Jaguar',
                'logo' => $this->filePath('icons/jaguar.png'),
                'logo_dark' => $this->filePath('icons/jaguar-w.png'),
                'logo_invert' => $this->filePath('icons/jaguar-w.png'),
            ],
            [
                'name' => 'Honda',
                'logo' => $this->filePath('icons/honda.png'),
                'logo_dark' => $this->filePath('icons/honda-w.png'),
                'logo_invert' => $this->filePath('icons/honda-w.png'),
            ],
            [
                'name' => 'Chevrolet',
                'logo' => $this->filePath('icons/chevrolet.png'),
                'logo_dark' => $this->filePath('icons/chevrolet-w.png'),
                'logo_invert' => $this->filePath('icons/chevrolet-w.png'),
            ],
            [
                'name' => 'Acura',
                'logo' => $this->filePath('icons/acura.png'),
                'logo_dark' => $this->filePath('icons/acura-w.png'),
                'logo_invert' => $this->filePath('icons/acura-w.png'),
            ],
            [
                'name' => 'BMW',
                'logo' => $this->filePath('icons/bmw.png'),
                'logo_dark' => $this->filePath('icons/bmw-w.png'),
                'logo_invert' => $this->filePath('icons/bmw-i.png'),
            ],
            [
                'name' => 'Toyota',
                'logo' => $this->filePath('icons/toyota.png'),
                'logo_dark' => $this->filePath('icons/toyota-w.png'),
                'logo_invert' => $this->filePath('icons/toyota-i.png'),
            ],
            [
                'name' => 'Ford',
                'logo' => $this->filePath('icons/ford-i.png'),
                'logo_dark' => $this->filePath('icons/ford-i.png'),
                'logo_invert' => $this->filePath('icons/ford-i.png'),
            ],
            [
                'name' => 'Nissan',
                'logo' => $this->filePath('icons/nissan-i.png'),
                'logo_dark' => $this->filePath('icons/nissan-w.png'),
                'logo_invert' => $this->filePath('icons/nissan-i.png'),
            ],
            [
                'name' => 'Opel',
                'logo' => $this->filePath('icons/opel-i.png'),
                'logo_dark' => $this->filePath('icons/opel-w.png'),
                'logo_invert' => $this->filePath('icons/opel-i.png'),
            ],
            [
                'name' => 'BMW',
                'logo' => $this->filePath('icons/bmw.png'),
                'logo_dark' => $this->filePath('icons/bmw-w.png'),
                'logo_invert' => $this->filePath('icons/bmw-i.png'),
            ],
            [
                'name' => 'Toyota',
                'logo' => $this->filePath('icons/toyota.png'),
                'logo_dark' => $this->filePath('icons/toyota-w.png'),
                'logo_invert' => $this->filePath('icons/toyota-i.png'),
            ],
        ];

        $makes = [...$makes];

        foreach ($makes as $make) {
            $carMake = CarMake::query()->create([
                'name' => $make['name'],
                'logo' => $make['logo'],
                'status' => BaseStatusEnum::PUBLISHED,
            ]);

            MetaBox::saveMetaBoxData($carMake, 'logo_dark', $make['logo_dark']);
            MetaBox::saveMetaBoxData($carMake, 'logo_invert', $make['logo_invert']);
        }
    }
}
