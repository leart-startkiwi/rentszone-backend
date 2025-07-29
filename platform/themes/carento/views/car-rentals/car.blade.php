@php
    $style = request()->query('style') ?: theme_option('car_detail_style', 'style-1');

    $style = in_array($style, ['style-1', 'style-2', 'style-3', 'style-4']) ? $style : 'style-1';
@endphp

{!! apply_filters('ads_render', null, 'car_before', ['class' => 'mb-2']) !!}

{!! Theme::partial('cars.car-detail.styles.' . $style, compact('car', 'reviews')) !!}

{!! apply_filters('ads_render', null, 'car_before', ['class' => 'mt-2']) !!}
