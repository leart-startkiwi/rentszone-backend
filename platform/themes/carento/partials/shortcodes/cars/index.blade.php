@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
    $buttonLabel = $shortcode->button_label;
    $buttonUrl = $shortcode->button_url;
@endphp

{!!
Theme::partial(
        "shortcodes.cars.styles.$style",
        compact(
            'shortcode',
            'cars',
            'title',
            'subtitle',
            'buttonLabel',
            'buttonUrl',
            'filterTypes',
            'carTypes',
            'fuelTypes',
        )
    )
!!}
