@php
    $style = $shortcode->style;
    $style = $style ? (in_array($style, array_map(function ($index) {
        return "style-$index";
    }, range(1, 3))) ? $style : 'style-1') : 'style-1';

    $currentCurrency = get_application_currency();

    $currency = $currentCurrency->title;

    $backgroundImage = $shortcode->background_image;
    $backgroundImage = $backgroundImage ? RvMedia::getImageUrl($backgroundImage) : null;
@endphp

{!! Theme::partial("shortcodes.car-loan-form.styles.$style", compact('shortcode', 'currency', 'backgroundImage')) !!}
