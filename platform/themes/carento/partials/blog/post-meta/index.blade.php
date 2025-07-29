@use(Theme\Carento\Support\ThemeHelper)

<div class="card-meta">
    @if (ThemeHelper::isShowPostMeta('list', 'published_date', true))
        {!! Theme::partial('blog.post-meta.published-date', compact('post')) !!}
    @endif

    @if (ThemeHelper::isShowPostMeta('list', 'reading_time', true))
        {!! Theme::partial('blog.post-meta.reading-time', compact('post')) !!}
    @endif

    @if (ThemeHelper::isShowPostMeta('list', 'views_count', true))
        {!! Theme::partial('blog.post-meta.views-count', compact('post')) !!}
    @endif
</div>
