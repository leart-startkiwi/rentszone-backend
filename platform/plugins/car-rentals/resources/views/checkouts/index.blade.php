@extends('plugins/car-rentals::checkouts.layouts.master')

@section('title', __('Checkout'))

@section('content')
    @if (is_plugin_active('payment') && $totalAmount)
        @include('plugins/payment::partials.header')
    @endif

    <div class="row checkout-form-wrapper">
        <div class="col-lg-7">
            <div class="d-block">
                @include('plugins/car-rentals::checkouts.partials.logo')
            </div>

            {!! $checkoutForm->renderForm() !!}
        </div>

        <div id="booking-information-block"
             data-update-service-url="{{ route('public.ajax.booking.services.update', $token) }}"
             data-url="{{ route('public.ajax.booking.update', $token) }}" class="col-lg-5 col-md-6 order-1 order-md-2">
            @include('plugins/car-rentals::checkouts.partials.booking-information', [
                'car' => $car,
                'amount' => $amount,
                'totalAmount' => $totalAmount,
                'taxTitle' => $taxTitle,
                'taxAmount' => $taxAmount,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'couponCode' => $couponCode ?? null,
                'couponAmount' => $couponAmount ?? null,
                'token' => $token,
                'rentalCarAmount' => $rentalCarAmount,
                'serviceIds' => $serviceIds ?? [],
                'services' => $services ?? []
            ])
        </div>
    </div>

    @if (is_plugin_active('payment'))
        @include('plugins/payment::partials.footer')
    @endif
@stop

@push('footer')
    <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js') }}?v=1.0.1"></script>
@endpush
