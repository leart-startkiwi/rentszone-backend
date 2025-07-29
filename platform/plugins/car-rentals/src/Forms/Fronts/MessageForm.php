<?php

namespace Botble\CarRentals\Forms\Fronts;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\CarRentals\Http\Requests\Fronts\MessageRequest;
use Botble\Theme\FormFront;

class MessageForm extends FormFront
{
    public static function formTitle(): string
    {
        return trans('plugins/car-rentals::message.form_name');
    }

    public function setup(): void
    {
        $customer = auth('customer')->user();

        $this
            ->contentOnly()
            ->setValidatorClass(MessageRequest::class)
            ->setUrl(route('car-rentals.message', $this->model['car_id']))
            ->setFormOption('class', 'bb-car-rentals-message-form')
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Name'))
                    ->required()
                    ->placeholder(__('Your name'))
                    ->disabled((bool) $customer?->name)
                    ->value($customer?->name),
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email'))
                    ->required()
                    ->placeholder(__('Your email address'))
                    ->disabled((bool) $customer?->email)
                    ->value($customer?->email),
            )
            ->add(
                'content',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Message'))
                    ->required()
                    ->placeholder(__('Type your message...'))
                    ->value(__('Hello, I am interested in your car rental service.'))
                    ->rows(5)
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Send message'))
                    ->attributes(['class' => 'btn btn-primary'])
            );
    }
}
