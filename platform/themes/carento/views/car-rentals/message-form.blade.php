<div @class(['form-contact-wrapper', $class ?? null])>
    <div class="h6 text-xl-bold neutral-1000">{{ __('Send Message') }}</div>

    {!! apply_filters('car_rentals_before_message_form', null, $car) !!}

    {!! \Botble\CarRentals\Forms\Fronts\MessageForm::createFromArray([
            'car_id' => $car->id,
        ])
        ->modify('submit', 'submit', ['attr' => ['class' => 'btn btn-book']])
        ->addBefore('content', 'data_name', 'text', ['label' => __('Subject'), 'attr' => ['value' => $car->name, 'disabled' => true]])
        ->renderForm()
    !!}

    {!! apply_filters('car_rentals_after_message_form', null, $car) !!}
</div>

