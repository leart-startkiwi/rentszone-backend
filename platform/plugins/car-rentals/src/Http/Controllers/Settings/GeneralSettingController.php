<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Forms\Settings\GeneralSettingForm;
use Botble\CarRentals\Http\Requests\Settings\GeneralSettingRequest;
use Botble\CarRentals\Models\CarCategory;
use Botble\Setting\Supports\SettingStore;

class GeneralSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/car-rentals::settings.general.title'));

        return GeneralSettingForm::create()->renderForm();
    }

    public function update(GeneralSettingRequest $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        $data = $request->validated();

        // Handle commission settings
        if ($request->input('enable_commission_fee_for_each_category')) {
            CarCategory::handleCommissionEachCategory($request->input('commission_by_category', []));
        }

        return $this->performUpdate($data);
    }
}
