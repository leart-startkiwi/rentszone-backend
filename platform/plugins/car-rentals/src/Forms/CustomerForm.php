<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\StoreCustomerRequest;
use Botble\CarRentals\Models\Customer;

class CustomerForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->setValidatorClass(StoreCustomerRequest::class)
            ->withCustomFields()
            ->columns()
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.customer.forms.name'))
                    ->required()
                    ->maxLength(120)
                    ->colspan(2)
            )
            ->add(
                'email',
                TextField::class,
                EmailFieldOption::make()
                    ->required()
                    ->colspan(1)
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.customer.forms.phone'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.customer.forms.phone_placeholder'))
                    ->maxLength(15)
                    ->colspan(1)
            )
            ->add(
                'dob',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->defaultValue(null)
                    ->label(trans('plugins/car-rentals::car-rentals.customer.forms.dob'))
                    ->colspan(1)
            )
            ->when($this->getModel()->getKey(), function (CustomerForm $form): void {
                $form->add(
                    'is_change_password',
                    OnOffField::class,
                    OnOffFieldOption::make()
                        ->label(trans('plugins/car-rentals::car-rentals.customer.forms.change_password'))
                        ->defaultValue(0)
                        ->colspan(2)
                );
            })
            ->add(
                'password',
                'password',
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.customer.forms.password'))
                    ->collapsible('is_change_password', 1, ! $this->getModel()->exists || $this->getModel()->is_change_password)
                    ->required()
                    ->maxLength(60)
                    ->colspan(1)
            )
            ->add(
                'password_confirmation',
                'password',
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.customer.forms.password_confirmation'))
                    ->collapsible('is_change_password', 1, ! $this->getModel()->exists || $this->getModel()->is_change_password)
                    ->required()
                    ->maxLength(60)
                    ->colspan(1)
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->add('avatar', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
