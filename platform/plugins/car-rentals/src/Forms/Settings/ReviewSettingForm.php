<?php

namespace Botble\CarRentals\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Settings\ReviewSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ReviewSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/car-rentals::settings.review.title'))
            ->setSectionDescription(trans('plugins/car-rentals::settings.review.description'))
            ->setFormOptions([
                'class' => 'main-setting-form',
            ])
            ->setValidatorClass(ReviewSettingRequest::class)
            ->add(
                'enabled_review',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->value(CarRentalsHelper::isEnabledCarReviews())
                    ->label(trans('plugins/car-rentals::settings.review.forms.enabled_review'))
            );
    }
}
