@php
    Theme::set('breadcrumb_simple', true);
    $customer = auth('customer')->user();
@endphp


@include('plugins/car-rentals::themes.customers.overview')