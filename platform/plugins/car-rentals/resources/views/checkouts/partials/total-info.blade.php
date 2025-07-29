
@if ($booking->sub_total != $booking->amount)
    @include('plugins/car-rentals::checkouts.partials.total-row', [
        'label' => __('Subtotal'),
        'value' => format_price($booking->sub_total),
    ])
@endif

@if ($booking->tax_amount)
    @php
        $taxInfo = '';
        if ($booking->car && $booking->car->car && $booking->car->car->exists) {
            $car = $booking->car->car;
            $taxInfo = $car->getTaxInfo($booking->tax_amount);
        }
    @endphp
    @include('plugins/car-rentals::checkouts.partials.total-row', [
        'label' => __('Tax') . (!empty($taxInfo) ? ' - <small class="d-inline-block text-muted tax-total-text">' . $taxInfo . '</small>' : ''),
        'value' => format_price($booking->tax_amount),
    ])
@endif

@if ((float) $booking->coupon_amount)
    @include('plugins/car-rentals::checkouts.partials.total-row', [
        'label' => __('Discount'),
        'value' =>
            format_price($booking->coupon_amount) .
            ($booking->coupon_code
                ? ' <small>(' . __('Using coupon code') . ': <strong>' . $booking->coupon_code . '</strong>)</small>'
                : ''),
    ])
@endif

{!! apply_filters('car_rentals_thank_you_total_info', null, $booking) !!}

<hr class="border-dark-subtle" />

<div class="row">
    <div class="col-6">
        <p>{{ __('Total') }}:</p>
    </div>
    <div class="col-6 float-end">
        <p class="total-text raw-total-text"> {{ format_price($booking->amount) }} </p>
    </div>
</div>
