<div class="my-3 bg-light">
    <div class="position-relative p-3" id="cart-item">
        <p>{{ __('Car Information:') }}</p>
        @if ($car)
            <div class="row cart-item">
                <div class="col-3">
                    <div class="checkout-product-img-wrapper">
                        {{ RvMedia::image($car->image, $car->name, 'thumb', attributes: ['class' => 'item-thumb img-thumbnail img-rounded']) }}
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-2">
                        {{ $car->name }}
                    </h5>
                    <p class="mb-0">
                        <span class="text-muted">{{ __('Rental Date:') }}</span> <span>{{ $startDate->toDateString() }} -> {{ $endDate->toDateString() }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">{{ __('Rate:') }}</span> <span>{{ format_price($car->rental_rate) }}/ {{ $car->rental_type->label() }}</span>
                    </p>

                    @if($pickupAddress = $car->pickup_address_text)
                        <p class="mb-0">
                            <span class="text-muted">{{ __('Pickup Address:') }}</span> <span>{{ $pickupAddress }}</span>
                        </p>
                    @endif

                    @if ($returnAddress = $car->return_address_text)
                        <p class="mb-0">
                            <span class="text-muted">{{ __('Return Address:') }}</span> <span>{{ $returnAddress }}</span>
                        </p>
                    @endif
                </div>
                <div class="col-auto text-end">
                    <p>{{ format_price($rentalCarAmount) }}</p>
                </div>
            </div>
        @endif

        @if(isset($services) && $services)
            <p>{{ __('Services:') }}</p>

            @foreach($services as $service)
                <div class="row cart-item">
                    <div class="col">
                        <p class="mb-2">
                            <strong>{{ $service->name }}</strong>
                        </p>
                    </div>
                    <div class="col-auto text-end">
                        <p class="mb-2">{{ format_price($service->price) }}</p>
                    </div>
                </div>
            @endforeach
        @endif

        <hr class="border-dark-subtle">

        <div class="mt-2 p-2">
            <div class="row">
                <div class="col-6">
                    <p>{{ __('Subtotal:') }}</p>
                </div>
                <div class="col-6">
                    <p class="price-text sub-total-text text-end">
                        {{ format_price($amount) }}
                    </p>
                </div>
            </div>

            @if($taxAmount)
                <div class="row">
                    <div class="col-6">
                        <p>{{ __('Tax') }} @if(!empty($taxTitle)) - <small class="d-inline-block text-muted tax-info-text">{{ $taxTitle }}</small>@endif</p>
                    </div>
                    <div class="col-6 float-end">
                        <p class="price-text tax-price-text text-end">
                            {{ format_price($taxAmount) }}
                        </p>
                    </div>
                </div>
            @endif

            @if ($couponCode && isset($couponAmount))
                <div class="row">
                    <div class="col-6">
                        <p>{{ __('Coupon code') }}:</p>
                    </div>
                    <div class="col-6 float-end">
                        <p class="price-text tax-price-text">
                            {{ $couponCode }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <p>{{ __('Coupon code discount amount') }}:</p>
                    </div>
                    <div class="col-6 float-end">
                        <p class="price-text tax-price-text">
                            {{ format_price($couponAmount) }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-6">
                    <p><strong>{{ __('Total') }}</strong>:</p>
                </div>
                <div class="col-6 float-end">
                    <p class="total-text raw-total-text" data-price="{{ $totalAmount }}">
                        {!! format_price($totalAmount) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-3 mb-5">
    <div class="checkout-discount-section">
        <a class="btn-open-coupon-form" href="#">
            {{ __('You have a coupon code?') }}
        </a>
    </div>
    <div class="coupon-wrapper mt-2" @if(! $couponCode) style="display: none;" @endif>
        @if(! $couponCode)
            @include('plugins/car-rentals::coupons.partials.apply-coupon')
        @else
            @include('plugins/car-rentals::coupons.partials.remove-coupon')
        @endif
    </div>
    <div class="clearfix"></div>
</div>
