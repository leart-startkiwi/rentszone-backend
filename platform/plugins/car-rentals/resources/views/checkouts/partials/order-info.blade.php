<div class="pt-3 mb-5 order-item-info">
    <div class="align-items-center">
        <h6 class="d-inline-block">{{ __('Booking ID') }}: {{ $booking->booking_number }}</h6>
    </div>

    <div class="checkout-success-products">
        <div id="cart-item-{{ $booking->id }}">
                @php
                    $bookingCar = $booking->car;
                    $services = $booking->services;
                @endphp

                @if($bookingCar)
                    <div class="row cart-item">
                        <div class="col-lg-3 col-md-3">
                            <div class="checkout-product-img-wrapper d-inline-block">
                                <img
                                    class="item-thumb img-thumbnail img-rounded mb-2 mb-md-0"
                                    src="{{ RvMedia::getImageUrl($bookingCar->car_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                    alt="{{ $bookingCar->car_name }}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <p class="mb-2">
                                @if ($bookingCar->car->exists && ($car = $bookingCar->car))
                                    <a href="{{ $car->url }}" target="_blank">{{ $car->name }}</a>
                                @else
                                    {{ $bookingCar->car_name }}
                                @endif
                            </p>

                            <p class="mb-2">
                                <strong>{{ __('Start Date') }}:</strong>
                                {{ $bookingCar->rental_start_date->toDateString() }}
                            </p>

                            <p class="mb-2 mb-md-0">
                                <strong>{{ __('End Date') }}:</strong>
                                {{ $bookingCar->rental_end_date->toDateString() }}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4 float-md-end text-md-end">
                            <p>{{ format_price($bookingCar->price) }}</p>
                        </div>
                    </div>
                @endif

                @if(isset($services) && $services->isNotEmpty())
                    <h6 class="mb-2 mt-4">{{ __('Services') }}</h6>

                    @foreach($services as $service)
                        <div class="row cart-item">
                            <div class="col">
                                <p class="mb-0">
                                    {{ $service->name }}
                                </p>
                            </div>
                            <div class="col-auto text-end">
                                <p class="mb-0">{{ format_price($service->price) }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif

            @if (!empty($isShowTotalInfo))
                @include('plugins/car-rentals::checkouts.partials.total-info', compact('booking'))
            @endif
        </div>
    </div>
</div>
