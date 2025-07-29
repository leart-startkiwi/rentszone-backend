@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header')
    @include('plugins/car-rentals::coupons.partials.trans')
    @include('plugins/car-rentals::coupons.partials.enums')
@endpush

@section('content')
    <x-core::form>
        <coupon-component
            currency="{{ get_application_currency()->symbol }}"
        ></coupon-component>
    </x-core::form>
@stop

@push('footer')
    {!! $jsValidation !!}
@endpush
