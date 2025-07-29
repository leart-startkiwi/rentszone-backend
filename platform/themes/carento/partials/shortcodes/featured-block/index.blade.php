@php
    $style = $shortcode->style;
    $style = $style ? (in_array($style, array_map(function ($index) {
        return "style-$index";
    }, range(1, 2))) ? $style : 'style-1') : 'style-1';

@endphp

{!! Theme::partial("shortcodes.featured-block.styles.$style", compact('shortcode', 'tabs')) !!}
