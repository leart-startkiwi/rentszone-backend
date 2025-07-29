@php
    $style = $shortcode->style;
    $style = $style ? (in_array($style, ['style-1', 'style-2', 'style-3']) ? $style : 'style-1') : 'style-1';
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
    $buttonLabel = $shortcode->button_label;
@endphp

{!! Theme::partial("shortcodes.blog-posts.styles.$style", compact('shortcode', 'title', 'subtitle', 'buttonLabel', 'posts', 'postCommentCount')) !!}
