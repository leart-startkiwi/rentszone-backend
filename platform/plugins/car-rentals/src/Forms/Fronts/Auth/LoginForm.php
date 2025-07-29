<?php

namespace Botble\CarRentals\Forms\Fronts\Auth;

use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\CarRentals\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\CarRentals\Http\Requests\Fronts\Auth\LoginRequest;
use Botble\CarRentals\Models\Customer;

class LoginForm extends AuthForm
{
    public static function formTitle(): string
    {
        return __('Customer login form');
    }

    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('customer.login.post'))
            ->setValidatorClass(LoginRequest::class)
            ->icon('ti ti-lock')
            ->heading(__('Login to your account'))
            ->description(__('Your personal data will be used to support your experience throughout this website, to manage access to your account.'))
            ->when(
                theme_option('login_background'),
                fn (AuthForm $form, string $background) => $form->banner($background)
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email'))
                    ->placeholder(__('Email address'))
                    ->icon('ti ti-mail')
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password'))
                    ->placeholder(__('Password'))
                    ->icon('ti ti-lock')
            )
            ->add('openRow', HtmlField::class, [
                'html' => '<div class="row g-0 mb-3">',
            ])
            ->add(
                'remember',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Remember me'))
                    ->wrapperAttributes(['class' => 'col-6'])
            )
            ->add(
                'forgot_password',
                HtmlField::class,
                [
                    'html' => Html::link(route('customer.password.reset'), __('Forgot password?'), attributes: ['class' => 'text-decoration-underline']),
                    'wrapper' => [
                        'class' => 'col-6 text-end',
                    ],
                ]
            )
            ->add('closeRow', HtmlField::class, [
                'html' => '</div>',
            ])
            ->submitButton(__('Login'), 'ti ti-arrow-narrow-right')
            ->when(CarRentalsHelper::isEnabledCustomerRegistration(), function (LoginForm $form): void {
                $form->add(
                    'register',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->view('plugins/car-rentals::customers.includes.register-link')
                );
            })
            ->add('filters', HtmlField::class, [
                'html' => apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, Customer::class),
            ]);
    }
}
