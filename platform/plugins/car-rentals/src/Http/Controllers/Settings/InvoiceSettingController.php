<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\CarRentals\Forms\Settings\InvoiceSettingForm;
use Botble\CarRentals\Http\Requests\Settings\InvoiceSettingRequest;

class InvoiceSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/car-rentals::settings.invoice.title'));

        return InvoiceSettingForm::create()->renderForm();
    }

    public function update(InvoiceSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
