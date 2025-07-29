@php
    Theme::set('pageTitle', $tag->name);
    $itemsPerRow = 3;
@endphp

@include(Theme::getThemeNamespace('views.loop'))
