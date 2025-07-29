<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Database\Seeders\Themes\Main;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->prepareRun();

        $this->call([
            Main\DatabaseSeeder::class,
        ]);

        $this->finished();
    }
}
