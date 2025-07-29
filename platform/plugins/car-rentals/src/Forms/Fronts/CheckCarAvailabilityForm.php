<?php

namespace Botble\CarRentals\Forms\Fronts;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DateField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\Theme\Facades\Theme;
use Botble\Theme\FormFront;

class CheckCarAvailabilityForm extends FormFront
{
    public function setup(): void
    {
        Theme::asset()->add('booking-css', 'vendor/core/plugins/car-rentals/css/front-booking-form.css', version: get_cms_version());

        $this
            ->contentOnly()
            ->setMethod('GET')
            ->setUrl(route('public.cars'))
            ->setFormOption('class', 'check-car-availability-form')
            ->columns(4)
            ->add(
                'pickup_location',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Pickup Location'))
                    ->choices($locationOptions = CarRentalsHelper::getLocations())
            )
            ->add(
                'drop_off_location',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Drop Off Location'))
                    ->choices($locationOptions)
            )
            ->add(
                'rental_start_date',
                DateField::class,
                TextFieldOption::make()
                    ->label(__('Start Date'))
            )
            ->add(
                'rental_end_date',
                DateField::class,
                TextFieldOption::make()->label(__('End Date'))
            )->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->cssClass('btn btn-primary')
                    ->label(__('Check Availability'))
            );
    }
}
