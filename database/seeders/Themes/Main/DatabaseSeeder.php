<?php

namespace Database\Seeders\Themes\Main;

use Botble\ACL\Database\Seeders\UserSeeder;
use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Database\Seeders\LanguageSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->prepareRun();

        $this->call($this->getSeeders());

        $this->finished();
    }

    protected function getSeeders(): array
    {
        return [
            SettingSeeder::class,
            SimpleSliderSeeder::class,
            PageSeeder::class,
            ThemeOptionSeeder::class,
            LanguageSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            AnnouncementSeeder::class,
            BlogSeeder::class,
            GallerySeeder::class,
            FaqSeeder::class,
            TeamSeeder::class,
            TestimonialSeeder::class,
            LocationSeeder::class,
            ...$this->carRentalSeeders(),
            WidgetSeeder::class,
            SimpleSliderSeeder::class,
        ];
    }

    protected function carRentalSeeders(): array
    {
        return [
            CustomerSeeder::class,
            CurrencySeeder::class,
            CarMakeSeeder::class,
            TaxSeeder::class,
            CarAttributeSeeder::class,
            CarColorSeeder::class,
            CarMaintenanceHistorySeeder::class,
            CouponSeeder::class,
            CarAddressSeeder::class,
            CarServiceSeeder::class,
            CarSeeder::class,
            CarReviewSeeder::class,
            CarCategorySeeder::class,
            BookingSeeder::class,
            MessageSeeder::class,
        ];
    }
}
