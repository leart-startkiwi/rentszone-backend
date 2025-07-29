<?php

namespace Botble\CarRentals\Forms\Fronts\Customers;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DateField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\TextField;
use Botble\CarRentals\Http\Requests\Fronts\Customers\EditCustomerRequest;
use Botble\CarRentals\Models\Customer;
use Botble\Theme\FormFront;

class CustomerForm extends FormFront
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->setUrl(route('customer.profile.post'))
            ->setValidatorClass(EditCustomerRequest::class)
            ->contentOnly()
            ->columns()
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Full Name'))
                    ->colspan(2)
                    ->required()
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->disabled()
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Phone'))
            )
            ->add(
                'dob',
                DateField::class,
                DatePickerFieldOption::make()
                    ->defaultValue(null)
                    ->colspan(2)
                    ->label(__('Date of birth'))
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Update'))
                    ->cssClass('btn btn-primary')
            );
    }
}
