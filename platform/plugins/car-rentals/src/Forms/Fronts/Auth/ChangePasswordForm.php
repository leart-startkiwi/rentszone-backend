<?php

namespace Botble\CarRentals\Forms\Fronts\Auth;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\Fronts\Auth\ChangePasswordRequest;
use Botble\CarRentals\Models\Customer;

class ChangePasswordForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->setUrl(route('customer.change-password.post'))
            ->setValidatorClass(ChangePasswordRequest::class)
            ->contentOnly()
            ->add(
                'old_password',
                PasswordField::class,
                TextFieldOption::make()
                    ->placeholder(__('Current password'))
                    ->label(__('Current password'))
                    ->required()
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->placeholder(__('New password'))
                    ->label(__('Password'))
                    ->required()
            )
            ->add(
                'password_confirmation',
                PasswordField::class,
                TextFieldOption::make()
                    ->placeholder(__('Confirm password'))
                    ->label(__('Password confirmation'))
                    ->required()
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Change password'))
                    ->cssClass('btn btn-primary')
            );
    }
}
