@php
    $style = $shortcode->style;
    $style = $style ? (in_array($style, ['style-1', 'style-2']) ? $style : 'style-1') : 'style-1';

    $title =  $shortcode->title;
    $subTitle =  $shortcode->sub_title;
    $buttonLabel =  $shortcode->button_label;
@endphp

{!! Theme::partial("shortcodes.car-types.styles.$style", compact('shortcode', 'carTypes', 'title', 'subTitle', 'buttonLabel')) !!}
