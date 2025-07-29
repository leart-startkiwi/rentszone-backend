<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\CarAddressRequest;
use Botble\CarRentals\Models\CarAddress;
use Botble\Location\Fields\Options\SelectLocationFieldOption;
use Botble\Location\Fields\SelectLocationField;

class CarAddressForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarAddress::class)
            ->setValidatorClass(CarAddressRequest::class)
            ->withCustomFields()
            ->when(
                is_plugin_active('location'),
                fn (FormAbstract $form) => $form->add(
                    'address',
                    SelectLocationField::class,
                    SelectLocationFieldOption::make()->colspan(3)
                )
            )
            ->add(
                'detail_address',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.attribute.address.form.detail_address'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.attribute.address.form.detail_address'))
                    ->required()
                    ->maxLength(300)
                    ->colspan(2)
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->setBreakFieldPoint('status');
    }
}
