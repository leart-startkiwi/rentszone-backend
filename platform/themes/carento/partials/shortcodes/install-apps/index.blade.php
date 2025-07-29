@php
    $title = $shortcode->title;
    $appsDescription = $shortcode->description;
    $androidAppImage = $shortcode->android_app_image;
    $androidAppUrl = $shortcode->android_app_url;
    $iosAppImage = $shortcode->ios_app_image;
    $iosAppUrl = $shortcode->ios_app_url;
    $decorImage = $shortcode->decor_image;
    $buttonLabel = $shortcode->button_label;
    $bgImage = $shortcode->background_image ? RvMedia::getImageUrl($shortcode->background_image) : null;
    $bgColor = $shortcode->background_color;

    $style = $shortcode->style;
    $style = $style ? (in_array($style, ['style-1', 'style-2', 'style-3']) ? $style : 'style-1') : 'style-1';
@endphp

{!! Theme::partial("shortcodes.install-apps.styles.$style", compact(
    'title',
    'appsDescription',
    'androidAppImage',
    'androidAppUrl',
    'iosAppImage',
    'iosAppUrl',
    'decorImage',
    'buttonLabel',
    'bgImage',
    'bgColor'
)) !!}
