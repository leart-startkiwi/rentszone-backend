@switch($sidebar)
    @case('off_canvas_sidebar')
        @include(Theme::getThemeNamespace('widgets.site-information.templates.includes.sidebars.off-canvas'))
        @break
    @default
        @include(Theme::getThemeNamespace('widgets.site-information.templates.includes.sidebars.default'))
@endswitch
