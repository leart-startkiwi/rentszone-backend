<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\Vendor\SettingForm;
use Botble\CarRentals\Http\Requests\Vendor\SettingRequest;
use Botble\CarRentals\Models\Customer;

class SettingController extends BaseController
{
    public function index()
    {
        $this->pageTitle(__('Settings'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/location/js/location.js',
            'vendor/core/plugins/car-rentals/js/car-form.js',
        ]);

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        return SettingForm::createFromModel($customer)
            ->renderForm();
    }

    public function update(SettingRequest $request)
    {
        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        // Save general settings
        SettingForm::createFromModel($customer)
            ->setRequest($request)
            ->save();

        // Save payout information
        if ($customer && $customer->getKey()) {
            $customer->payout_payment_method = $request->input('payout_payment_method');
            $customer->bank_info = $request->input('bank_info', []);
            $customer->save();
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.vendor.settings.index'))
            ->withUpdatedSuccessMessage();
    }
}
