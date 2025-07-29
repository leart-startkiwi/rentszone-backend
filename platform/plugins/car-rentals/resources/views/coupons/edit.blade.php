@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header')
    @include('plugins/car-rentals::coupons.partials.trans')
    @include('plugins/car-rentals::coupons.partials.enums')

    {!! JsValidator::formRequest(\Botble\CarRentals\Http\Requests\CouponRequest::class) !!}
@endpush

@section('content')
    <x-core::form>
        <coupon-component
            currency="{{ get_application_currency()->symbol }}"
            :coupon="{{ $coupon }}"
        ></coupon-component>
    </x-core::form>
@stop
