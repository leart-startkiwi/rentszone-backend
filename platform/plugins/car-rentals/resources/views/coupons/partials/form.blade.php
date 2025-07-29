@php
    $couponCode = \Botble\CarRentals\Facades\BookingHelper::getCheckoutData('coupon_code');
    $couponAmount = \Botble\CarRentals\Facades\BookingHelper::getCheckoutData('coupon_amount');
@endphp
<div
    class="checkout-discount-section"
    @if (session()->has('applied_coupon_code')) style="display: none;" @endif
>
    <a class="btn-open-coupon-form" href="#">
        {{ __('You have a coupon code?') }}
    </a>
</div>
<div
    class="coupon-wrapper mt-2"
    @if (!session()->has('applied_coupon_code')) style="display: none;" @endif
>
    @if (!session()->has('applied_coupon_code'))
        @include('car-rentals::coupons.partials.apply-coupon')
    @else
        @include('car-rentals::coupons.partials.remove-coupon')
    @endif
</div>
<div class="clearfix"></div>
