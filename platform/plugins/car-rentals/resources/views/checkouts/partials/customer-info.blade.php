<div class="order-customer-info">
    <h3> {{ __('Customer information') }}</h3>

    @if ($booking->customer_name)
        <p>
            <span class="d-inline-block">{{ __('Full name') }}:</span>
            <span class="order-customer-info-meta">{{ $booking->customer_name }}</span>
        </p>
    @endif

    @if ($booking->customer_phone)
        <p>
            <span class="d-inline-block">{{ __('Phone') }}:</span>
            <span class="order-customer-info-meta">{{ $booking->customer_phone }}</span>
        </p>
    @endif

    @if ($booking->customer_email)
        <p>
            <span class="d-inline-block">{{ __('Email') }}:</span>
            <span class="order-customer-info-meta">{{ $booking->customer_email }}</span>
        </p>
    @endif

    @if (is_plugin_active('payment') && $booking->payment->id)
        <p>
            <span class="d-inline-block">{{ __('Payment method') }}:</span>
            <span class="order-customer-info-meta">{{ $booking->payment->payment_channel->label() }}</span>
        </p>
        <p>
            <span class="d-inline-block">{{ __('Payment status') }}:</span>
            <span
                class="order-customer-info-meta"
                style="text-transform: uppercase"
                data-bb-target="ecommerce-order-payment-status"
            >{!! BaseHelper::clean($booking->payment->status->toHtml()) !!}</span>
        </p>

        @if (setting('payment_bank_transfer_display_bank_info_at_the_checkout_success_page', false) &&
                ($bankInfo = OrderHelper::getOrderBankInfo($bookings)))
            {!! $bankInfo !!}
        @endif
    @endif

    {!! apply_filters('car_rentals_thank_you_customer_info', null, $booking) !!}
</div>
