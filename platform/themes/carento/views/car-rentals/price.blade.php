<div class="card-price">
    <p>
        @if ($car->is_for_sale)
            <span class="text-md-medium neutral-500">{{ __('Price') }}: </span><span class="heading-6 neutral-1000 car-price-text">{{ format_price($car->sale_price) }}</span>
        @elseif (CarRentalsHelper::isRentalBookingEnabled())
            <span class="heading-6 neutral-1000 car-price-text">{{ format_price($car->rental_rate) }}</span><span class="mx-1">/</span><span class="neutral-500 car-rental-period-text">{{ $car->rental_type->shortLabel() }}</span>
        @endif
    </p>
</div>
