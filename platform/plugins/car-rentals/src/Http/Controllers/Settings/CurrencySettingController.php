<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\CarRentals\Forms\Settings\CurrencySettingForm;
use Botble\CarRentals\Http\Requests\Settings\CurrencySettingRequest;
use Botble\CarRentals\Services\StoreCurrenciesService;

class CurrencySettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/car-rentals::currency.currencies'));

        $form = CurrencySettingForm::create();

        return view('plugins/car-rentals::settings.currency', compact('form'));
    }

    public function update(CurrencySettingRequest $request, StoreCurrenciesService $service)
    {
        $this->saveSettings($request->except([
            'currencies',
            'currencies_data',
            'deleted_currencies',
        ]));

        $currencies = json_decode($request->validated('currencies'), true) ?: [];

        if (! $currencies) {
            return $this
                ->httpResponse()
                ->setNextUrl(route('car-rentals.settings.currencies'))
                ->setError()
                ->setMessage(trans('plugins/car-rentals::currency.require_at_least_one_currency'));
        }

        $deletedCurrencies = json_decode($request->input('deleted_currencies', []), true) ?: [];

        $service->execute($currencies, $deletedCurrencies);

        return $this
            ->httpResponse()
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
