@php
    $bgColor = theme_option('header_top_background_color', '#313131');
    $textColor = theme_option('header_top_text_color', '#ffffff');
    $announcementDisplayHtml = apply_filters('announcement_display_html');
@endphp

<div class="top-bar top-bar-2 top-bar-3"
    @style([
        "--header-top-background-color: $bgColor" => $bgColor,
        "--header-top-text-color: $textColor" => $textColor,
    ])
>
    <div class="container-fluid">
        {!! dynamic_sidebar('header_top_sidebar') !!}
        @if($announcementDisplayHtml)
            <div class="text-header">
                {!! $announcementDisplayHtml !!}
            </div>
        @endif

        <div class="top-right-header">
            {!! Theme::partial('header.action-buttons.partials.language-switcher') !!}

            {!! Theme::partial('header.action-buttons.partials.currency-switcher') !!}

            @if (theme_option('hide_theme_mode_switcher', 'no') !== 'yes')
                <div class="top-button-mode">
                    <a class="btn btn-mode change-mode" href="#" title="{{ __('Toggle dark/light mode') }}" data-bs-theme-value="dark">
                        <img class="light-mode" src="{{ Theme::asset()->url('images/icons/light.svg') }}" alt="{{ __('Light mode') }}" />
                        <img class="dark-mode" src="{{ Theme::asset()->url('images/icons/light-w.svg') }}" alt="{{ __('Dark mode') }}" />
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
