<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Shortcode\View\View;
use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these events can be overridden by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function ($theme): void {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme): void {
            // Partial composer.
            // $theme->partialComposer('header', function($view) {
            //     $view->with('auth', \Auth::user());
            // });

            // You may use this event to set up your assets.

            $version = get_cms_version() . '.1';

            $theme->asset()->usePath()->add('tobii-css', 'plugins/tobii/css/tobii.min.css');
            $theme->asset()->usePath()->add('animate-css', 'plugins/animate/animate.min.css');
            $theme->asset()->usePath()->add('magnific-popup-css', 'plugins/magnific-popup/magnific-popup.css');
            $theme->asset()->usePath()->add('odometer-css', 'plugins/odometer/odometer.css');
            $theme->asset()->usePath()->add('perfect-scrollbar-css', 'plugins/perfect-scrollbar/perfect-scrollbar.css');
            $theme->asset()->usePath()->add('select2-css', 'plugins/select2/select2.min.css');
            $theme->asset()->usePath()->add('slick-css', 'plugins/slick/slick.min.css');
            $theme->asset()->usePath()->add('swiper-css', 'plugins/swiper/swiper-bundle.min.css');
            $theme->asset()->usePath()->add('datepicker-css', 'plugins/bootstrap/bootstrap-datepicker.css');
            $theme->asset()->usePath()->add('datepicker3-css', 'plugins/bootstrap/bootstrap-datepicker3.css');

            if (BaseHelper::isRtlEnabled()) {
                $theme->asset()->usePath()->add('bootstrap-css', 'plugins/bootstrap/bootstrap.rtl.min.css');
            } else {
                $theme->asset()->usePath()->add('bootstrap-css', 'plugins/bootstrap/bootstrap.min.css');
            }

            $theme->asset()->usePath()->add('style', 'css/style.css', version: $version);

            $theme->asset()->container('footer')->usePath()->add('jquery', 'plugins/jquery/jquery.min.js');
            $theme->asset()->container('footer')->usePath()->add('jquery-ui', 'plugins/jquery/jquery-ui.js');
            $theme->asset()->container('footer')->usePath()->add('bootstrap-js', 'plugins/bootstrap/bootstrap.bundle.min.js');
            $theme->asset()->container('footer')->usePath()->add('bootstrap-datepicker-js', 'plugins/bootstrap/bootstrap-datepicker.js');
            $theme->asset()->container('footer')->usePath()->add('counterup-js', 'plugins/counterup/counterup.js');
            $theme->asset()->container('footer')->usePath()->add('jquery-appear-js', 'plugins/jquery/jquery.appear.js');
            $theme->asset()->container('footer')->usePath()->add('jquery-odometer-js', 'plugins/jquery/jquery.odometer.min.js');
            $theme->asset()->container('footer')->usePath()->add('jquery-theia-sticky-js', 'plugins/jquery/jquery.theia.sticky.js');
            $theme->asset()->container('footer')->usePath()->add('jquery-vticker-js', 'plugins/jquery/jquery.vticker-min.js');
            $theme->asset()->container('footer')->usePath()->add('magnific-popup-js', 'plugins/magnific-popup/magnific-popup.js');
            $theme->asset()->container('footer')->usePath()->add('masonry-js', 'plugins/masonry/masonry.min.js');
            $theme->asset()->container('footer')->usePath()->add('slick-js', 'plugins/slick/slick.min.js');
            $theme->asset()->container('footer')->usePath()->add('swiper-js', 'plugins/swiper/swiper-bundle.min.js');
            $theme->asset()->container('footer')->usePath()->add('wow-js', 'plugins/wow/wow.js');
            $theme->asset()->container('footer')->usePath()->add('select2-js', 'plugins/select2/select2.min.js');
            $theme->asset()->container('footer')->usePath()->add('scrollup-js', 'js/scrollup.js');
            $theme->asset()->container('footer')->usePath()->add('dark-js', 'js/dark.js');
            $theme->asset()->container('footer')->usePath()->add('perfect-scrollbar-js', 'plugins/perfect-scrollbar/perfect-scrollbar.min.js');
            $theme->asset()->container('footer')->usePath()->add('tobii-js', 'plugins/tobii/js/tobii.min.js');

            $theme->asset()->container('footer')->usePath()->add(
                'main-js',
                'js/main.js',
                ['jquery'],
                version: $version
            );

            $theme->asset()->container('footer')->usePath()->add(
                'custom-js',
                'js/custom.js',
                ['jquery'],
                version: $version
            );

            if (function_exists('shortcode')) {
                $theme->composer([
                    'page',
                    'post',
                    'car-rentals.service',
                    'car-rentals.car',
                ], function (View $view): void {
                    $view->withShortcodes();
                });
            }

            $theme->addBodyAttributes(['theme-mode' => theme_option('default_theme_color_mode', 'light')]);
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [
            'default' => function ($theme): void {
                //
            },
        ],
    ],
];
