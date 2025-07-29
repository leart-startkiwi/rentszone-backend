<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController as BaseSettingController;

class SettingController extends BaseSettingController
{
    protected function saveSettings(array $data, string $prefix = ''): void
    {
        parent::saveSettings($data, 'car_rentals_');
    }
}
