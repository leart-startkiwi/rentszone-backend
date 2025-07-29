<?php

namespace Database\Seeders\Themes\Home3;

class DatabaseSeeder extends \Database\Seeders\Themes\Main\DatabaseSeeder
{
    public function getSeeders(): array
    {
        return [
            ...parent::getSeeders(),
            PageSeeder::class,
            ThemeOptionSeeder::class,
        ];
    }
}
