<?php

namespace Botble\CarRentals\Forms\Settings;

use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Settings\CarFilterSettingRequest;
use Botble\Setting\Forms\SettingForm;

class CarFilterSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/car-rentals::settings.car_filter.title'))
            ->setSectionDescription(trans('plugins/car-rentals::settings.car_filter.description'))
            ->setValidatorClass(CarFilterSettingRequest::class)
            ->setFormOptions([
                'class' => 'main-setting-form',
            ])
            ->add(
                'enabled_car_filter',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.car_filter.forms.enable_car_filter'))
                    ->value(CarRentalsHelper::isEnabledCarFilter())
            )
            ->add(
                'filter_cars_by[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->collapsible('enabled_car_filter', 1, CarRentalsHelper::isEnabledCarFilter())
                    ->label(trans('plugins/car-rentals::settings.car_filter.forms.filter_cars_by'))
                    ->choices([
                        'vehicle_condition' => trans('plugins/car-rentals::settings.car_filter.forms.vehicle_condition'),
                        'locations' => trans('plugins/car-rentals::settings.car_filter.forms.locations'),
                        'prices' => trans('plugins/car-rentals::settings.car_filter.forms.prices'),
                        'categories' => trans('plugins/car-rentals::settings.car_filter.forms.categories'),
                        'colors' => trans('plugins/car-rentals::settings.car_filter.forms.colors'),
                        'types' => trans('plugins/car-rentals::settings.car_filter.forms.types'),
                        'transmissions' => trans('plugins/car-rentals::settings.car_filter.forms.transmissions'),
                        'fuels' => trans('plugins/car-rentals::settings.car_filter.forms.fuels'),
                        'review_scores' => trans('plugins/car-rentals::settings.car_filter.forms.review_scores'),
                        'addresses' => trans('plugins/car-rentals::settings.car_filter.forms.addresses'),
                    ])
                    ->selected(CarRentalsHelper::getCarsFilterBy())
            );
    }
}
