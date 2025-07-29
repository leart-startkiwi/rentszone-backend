<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\CarRentals\Forms\Settings\CustomerSettingForm;
use Botble\CarRentals\Http\Requests\Settings\CustomerSettingRequest;

class CustomerSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/car-rentals::settings.customer.title'));

        return CustomerSettingForm::create()->renderForm();
    }

    public function update(CustomerSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
