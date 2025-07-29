<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\InputFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\DateField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\CarRentals\Http\Requests\CarMaintenanceHistoryRequest;
use Botble\CarRentals\Models\CarMaintenanceHistory;
use Botble\CarRentals\Models\Currency;

class CarMaintenanceHistoryForm extends FormAbstract
{
    public function setup(): void
    {
        $currencies = Currency::query()->select(['id', 'title'])->pluck('title', 'id')->all();

        $this
            ->model(CarMaintenanceHistory::class)
            ->contentOnly()
            ->setValidatorClass(CarMaintenanceHistoryRequest::class)
            ->setUrl(route('car-rentals.car-maintenance-histories.store'))
            ->add('car_id', 'hidden', TextFieldOption::make()->value($this->getModel()['car']->id ?? $this->getModel()->car_id))
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->maxLength(255)
                    ->colspan(1)
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add(
                'currency_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->required()
                    ->label(trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.currency'))
                    ->choices($currencies)
                    ->colspan(1)
            )
            ->add(
                'amount',
                NumberField::class,
                NumberFieldOption::make()
                    ->required()
                    ->label(trans('plugins/car-rentals::car-rentals.car.maintenance_history.amount'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.price_placeholder'))
                    ->required()
            )
            ->add('date', DateField::class, InputFieldOption::make()->label(trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.date')))
            ->setBreakFieldPoint('status');
    }
}
