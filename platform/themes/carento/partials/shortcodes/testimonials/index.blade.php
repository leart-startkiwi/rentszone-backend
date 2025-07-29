@php
    $style = $shortcode->style;
    $style = $style ? (in_array($style, array_map(function ($index) {
        return "style-$index";
    }, range(1, 3))) ? $style : 'style-1') : 'style-1';

@endphp

{!! Theme::partial("shortcodes.testimonials.styles.$style", compact('shortcode', 'testimonials')) !!}
