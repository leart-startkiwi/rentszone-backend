@php
    $itemsPerRow = $itemsPerRow ?? null;
    $displayTitle = $displayTitle ?? false;
    $displayDescription = $displayDescription ?? false;
@endphp

<div>
    @if ($displayTitle && ($title = theme_option('blog_post_list_page_title')))
        <h2 class="neutral-1000">{{ $title }}</h2>
    @endif

    @if ($displayDescription && ($description = theme_option('blog_post_list_page_description')))
        <p class="text-xl-medium neutral-500">{!! $description !!}</p>
    @endif

    {!! apply_filters('ads_render', null, 'post_list_before', ['class' => 'mb-2']) !!}

    {!! Theme::partial('blog.posts', compact('posts', 'itemsPerRow')) !!}

    @if ($posts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $posts->total() > 0)
        {{ $posts->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
    @endif

    {!! apply_filters('ads_render', null, 'post_list_after', ['class' => 'mt-2']) !!}
</div>
