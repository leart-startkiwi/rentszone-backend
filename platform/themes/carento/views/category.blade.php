@php
    Theme::set('pageTitle', $category->name);
    $itemsPerRow = 3;
@endphp

@include(Theme::getThemeNamespace('views.loop'))
