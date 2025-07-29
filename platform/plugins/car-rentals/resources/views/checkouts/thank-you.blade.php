@extends('plugins/car-rentals::checkouts.layouts.master')

@section('title', __('Order successfully. Order number :id', ['id' => $booking->booking_number]))

@section('content')
    <div class="row">
        <div class="col-lg-7 col-md-6 col-12">
            @include('plugins/car-rentals::checkouts.partials.logo')

            <div class="thank-you">
                <x-core::icon name="ti ti-circle-check-filled" />

                <div class="d-inline-block">
                    <h3 class="thank-you-sentence">
                        {{ __('Your booking is successfully placed') }}
                    </h3>
                    <p>{{ __('Thank you for choosing our service!') }}</p>
                </div>
            </div>


            @include('plugins/car-rentals::checkouts.partials.customer-info', ['customer' => $booking->customer, 'booking' => $booking])

            <a class="btn payment-checkout-btn" href="{{ BaseHelper::getHomepageUrl() }}">
                {{ __('Back to home') }}
            </a>
        </div>
        <div class="col-lg-5 col-md-6 mt-5 mt-md-0 mb-5">
            <div class="my-3 bg-light p-3">
                @include('plugins/car-rentals::checkouts.partials.order-info')

                @include('plugins/car-rentals::checkouts.partials.total-info', ['booking' => $booking])
            </div>
        </div>
    </div>
@stop
