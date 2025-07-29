@php
    $blogPostStyle = request()->get('style', theme_option('blog_post_style', 'grid'));
    $blogPostStyle = in_array($blogPostStyle, ['grid', 'list']) ? $blogPostStyle : 'grid';
    $itemsPerRow = $itemsPerRow ?? null;
@endphp

@if($posts->isNotEmpty())
    {!! Theme::partial("blog.styles.$blogPostStyle", compact('posts', 'itemsPerRow')) !!}
@else
    <div class="text-center text-muted">
        <h6>{{ __('No posts found') }}</h6>
    </div>
@endif
