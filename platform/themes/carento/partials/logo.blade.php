@php
    $logo = $logo ?? theme_option('logo');

    $height = theme_option('logo_height', 25);
    $attributes = [
        'style' => sprintf('height: %s', is_numeric($height) ? "{$height}px" : $height),
        'loading' => false,
    ];

    $wrapperClass ??= null;
@endphp

@if ($logo)
    <div @class(['header-logo', $wrapperClass])>
        @php
            $siteTitle = theme_option('site_title');
        @endphp

        <a class="d-flex" href="{{ BaseHelper::getHomepageUrl() }}">
            @if ($logo = theme_option('logo'))
                {{ RvMedia::image($logo, $siteTitle, attributes: ['class' => 'light-mode', ...$attributes]) }}
            @endif

            @if ($logoDark = theme_option('logo_dark'))
                {{ RvMedia::image($logoDark, $siteTitle, attributes: ['class' => 'dark-mode', ...$attributes]) }}
            @endif
        </a>
    </div>
@endif
