<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\CarRentals\Forms\Settings\ReviewSettingForm;
use Botble\CarRentals\Http\Requests\Settings\ReviewSettingRequest;

class ReviewSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/car-rentals::settings.review.title'));

        return ReviewSettingForm::create()->renderForm();
    }

    public function update(ReviewSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
