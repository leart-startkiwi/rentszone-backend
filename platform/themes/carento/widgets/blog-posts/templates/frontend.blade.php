@switch($sidebar)
    @case('off_canvas_sidebar')
        @include(Theme::getThemeNamespace('widgets.blog-posts.templates.includes.sidebars.off-canvas'))
        @break
    @default
        @include(Theme::getThemeNamespace('widgets.blog-posts.templates.includes.sidebars.default'))
@endswitch
