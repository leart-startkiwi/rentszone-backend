<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\CarRentals\Forms\Settings\TaxSettingForm;
use Botble\CarRentals\Http\Requests\Settings\TaxSettingRequest;
use Botble\CarRentals\Tables\TaxTable;
use Illuminate\Http\Request;

class TaxSettingController extends SettingController
{
    public function edit(Request $request, TaxTable $taxTable)
    {
        if ($request->expectsJson()) {
            return $taxTable->renderTable();
        }

        $this->pageTitle(trans('plugins/car-rentals::settings.tax.name'));

        $form = TaxSettingForm::create();

        return view('plugins/car-rentals::settings.tax', compact('taxTable', 'form'));
    }

    public function update(TaxSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
