@php
    $style = theme_option('header_style', 2);
    $style = in_array($style, [1, 2]) ? $style : 1;
@endphp

{!! Theme::partial("header.action-buttons.styles.style-$style") !!}
