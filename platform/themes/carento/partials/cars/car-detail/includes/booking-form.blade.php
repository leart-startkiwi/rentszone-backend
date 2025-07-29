@if (CarRentalsHelper::isRentalBookingEnabled())
    <div class="booking-form">
        <div class="head-booking-form">
            <p class="text-xl-bold neutral-1000">{{ __('Rent This Vehicle') }}</p>
        </div>
        <div class="content-booking-form">
            @if ($car->hasExternalBookingUrl())
                <div class="text-center">
                    <a href="{{ $car->getExternalBookingRoute() }}" class="btn btn-book w-100">{{ __('Book Now') }}</a>
                    <p class="mt-2 small text-muted">{{ __('You will be redirected to an external booking site') }}</p>
                </div>
            @else
                {!!
                    \Botble\CarRentals\Forms\Fronts\BookingForm::createFromArray([
                        'car_id' => $car->id,
                    ])
                    ->modify('submit', 'submit', ['attr' => ['class' => 'btn btn-book']])
                    ->renderForm()
                !!}
            @endif
        </div>
    </div>
@endif
