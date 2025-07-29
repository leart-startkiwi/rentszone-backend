@php
    $isHeaderTransparent = theme_option('is_header_transparent', false) && Theme::get('isHomepage');
@endphp

{!! apply_filters('ads_render', null, 'header_before', ['class' => 'mb-2']) !!}

<header @class(['header sticky-bar', 'header-fixed header-transparent' => $isHeaderTransparent, 'header-home-2' => ! $isHeaderTransparent])>
    @if (theme_option('display_header_top', true))
        {!! Theme::partial('header.header-top') !!}
    @endif

    <div @class(['container-fluid', 'background-body' => ! $isHeaderTransparent])>
        <div class="main-header">
            <div class="header-left">
                {!! Theme::partial('logo') !!}

                <div class="header-nav">
                    <nav class="nav-main-menu">
                        {!!
                            Menu::renderMenuLocation('main-menu', [
                                'options' => ['class' => 'main-menu mb-0'],
                                'view'    => 'main-menu',
                            ])
                        !!}
                    </nav>
                </div>

                <div class="header-right">
                    {!! Theme::partial('header.action-buttons.index') !!}

                    <div class="burger-icon-2 burger-icon-white">
                        <img src="{{ Theme::asset()->url('images/icons/menu.svg') }}" alt="Menu Icon">
                    </div>
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"> </span>
                        <span class="burger-icon-bottom"> </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{!! apply_filters('ads_render', null, 'header_after', ['class' => 'mt-2']) !!}
