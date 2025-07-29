<?php

namespace Botble\CarRentals\Forms\Fronts;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextField;
use Botble\CarRentals\Http\Requests\Fronts\CheckoutRequest;
use Botble\CarRentals\Models\Service;
use Botble\Theme\FormFront;

class CheckoutForm extends FormFront
{
    public function setup(): void
    {
        $customer = auth('customer')->user();

        $services = Service::query()->select(['id', 'name', 'price'])->wherePublished()->get();

        $serviceOptions = [];

        foreach ($services as $service) {
            $serviceOptions[$service->id] = $service->name . ' - ' . format_price($service->price);
        }

        $this
            ->setUrl(route('public.checkout.post'))
            ->setValidatorClass(CheckoutRequest::class)
            ->contentOnly()
            ->columns()
            ->when($customer, function (CheckoutForm $form) use ($customer): void {
                $form->add(
                    'customer_id',
                    'hidden',
                    TextFieldOption::make()
                        ->value($customer->getKey())
                );
            }, function (CheckoutForm $form): void {
                $form->add(
                    'login_html',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content(view('plugins/car-rentals::checkouts.partials.login'))
                        ->colspan(2)
                );
            })
            ->add(
                'customer_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Full Name'))
                    ->placeholder(__('Enter your full name'))
                    ->colspan(2)
                    ->required()
                    ->when($customer, function (TextFieldOption $option) use ($customer): void {
                        $option->value($customer->name);
                    })
            )
            ->add(
                'customer_email',
                TextField::class,
                TextFieldOption::make()
                ->label(__('Email'))
                ->placeholder(__('Enter your email'))
                ->required()
                ->when($customer, function (TextFieldOption $option) use ($customer): void {
                    $option->value($customer->email);
                })
            )
            ->add(
                'customer_phone',
                TextField::class,
                TextFieldOption::make()
                ->label(__('Phone'))
                ->placeholder(__('Enter your phone number'))
                ->required()
                ->when($customer, function (TextFieldOption $option) use ($customer): void {
                    $option->value($customer->phone);
                })
            )
            ->when(! $customer, function (CheckoutForm $form): void {
                $form->add(
                    'is_register',
                    OnOffCheckboxField::class,
                    OnOffFieldOption::make()
                        ->label(__('Register an account with above information?'))
                        ->colspan(2)
                )
                    ->add('register_html_open', HtmlField::class, HtmlFieldOption::make()->content('<div class="row w-100" style="display:none;" id="register-form">'))
                    ->add(
                        'password',
                        'password',
                        TextFieldOption::make()
                            ->label(__('Password'))
                            ->placeholder(__('Enter your password'))
                            ->colspan(1)
                    )
                    ->add(
                        'password_confirmation',
                        'password',
                        TextFieldOption::make()
                            ->label(__('Confirm Password'))
                            ->placeholder(__('Enter your password again'))
                            ->colspan(1)
                    )
                    ->add('register_html_close', HtmlField::class, HtmlFieldOption::make()->content('</div>'));
            })
            ->add(
                'service_ids[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(__('Additional Services'))
                    ->choices($serviceOptions)
                    ->selected($this->model['serviceIds'] ?? [])
                    ->colspan(2)
            )
            ->add(
                'payment_methods',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(
                        view(
                            'plugins/car-rentals::checkouts.partials.payment-methods',
                            [
                                'totalAmount' => $this->model['totalAmount'],
                                'taxAmount' => $this->model['taxAmount'] ?? 0,
                                'taxTitle' => $this->model['taxTitle'] ?? '',
                                'subtotal' => $this->model['amount'] ?? 0,
                                'couponAmount' => $this->model['couponAmount'] ?? 0,
                                'couponCode' => $this->model['couponCode'] ?? '',
                            ]
                        )
                    )
                    ->colspan(2)
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Checkout'))
                    ->cssClass('btn btn-primary')
                    ->colspan(2)
            );
    }
}
