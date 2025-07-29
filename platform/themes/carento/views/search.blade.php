@php
    Theme::set('pageTitle', __('Search result for: ":query"', ['query' => BaseHelper::stringify(request()->input('q'))]));
    $itemsPerRow = 3;
@endphp

@include(Theme::getThemeNamespace('views.loop'))
