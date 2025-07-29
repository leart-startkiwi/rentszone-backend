<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\CarRentals\Forms\Settings\CarFilterSettingForm;
use Botble\CarRentals\Http\Requests\Settings\CarFilterSettingRequest;

class CarFilterSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/car-rentals::settings.car_filter.title'));

        return CarFilterSettingForm::create()->renderForm();
    }

    public function update(CarFilterSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
