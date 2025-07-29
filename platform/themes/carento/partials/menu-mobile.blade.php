<div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar button-bg-2">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-logo">
            {!! Theme::partial('logo') !!}
            <div class="burger-icon burger-icon-white"></div>
        </div>

        <div class="mobile-header-content-area">
                <div class="mobile-menu-wrap mobile-header-border">
                    <nav>
                        {!!
                            Menu::renderMenuLocation('main-menu', [
                                'options' => ['class' => 'mobile-menu font-heading'],
                                'view'    => 'main-menu',
                            ])
                        !!}
                    </nav>
                </div>

                <div class="mobile-menu-wrap mobile-header-border">
                    <nav>
                        {!! Theme::partial('currency-switcher-mobile') !!}
                        {!! Theme::partial('language-switcher-mobile') !!}
                    </nav>
                </div>
            </div>
    </div>
</div>
