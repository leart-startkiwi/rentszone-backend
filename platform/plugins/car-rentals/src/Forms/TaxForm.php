<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\TaxRequest;
use Botble\CarRentals\Models\Tax;

class TaxForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tax::class)
            ->setValidatorClass(TaxRequest::class)
            ->columns()
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->colspan(2)
            )
            ->add(
                'percentage',
                NumberField::class,
                NumberFieldOption::make()
                    ->required()
                    ->label(trans('plugins/car-rentals::car-rentals.tax.forms.percentage'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.tax.forms.percentage_placeholder'))
                    ->min(0)
                    ->max(99.99)
                    ->step(0.01)
                    ->defaultValue(0)
            )
            ->add(
                'priority',
                NumberField::class,
                NumberFieldOption::make()
                    ->required()
                    ->label(trans('plugins/car-rentals::car-rentals.tax.forms.priority'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.tax.forms.priority_placeholder'))
                    ->min(1)
                    ->max(1000)
                    ->defaultValue(1)
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
