@if (CarRentalsHelper::isRentalBookingEnabled() && ! $car->is_for_sale)
    <div class="card-button"><a class="btn btn-gray" href="{{ $car->url }}">{{ __('Book Now') }}</a></div>
@endif
