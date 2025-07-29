@use(Theme\Carento\Support\ThemeHelper)

<div class="card-news background-card hover-up mb-4">
    <a href="{{ $post->url }}">
        {{ RvMedia::image($post->image, $post->name, 'medium-rectangle') }}
    </a>
    <div class="card-info">
        {!! Theme::partial('blog.post-meta.floating-category-badge', compact('post')) !!}

        {!! Theme::partial('blog.post-meta.index', compact('post')) !!}

        <div class="card-title">
            <a class="text-xl-bold neutral-1000 truncate-2-custom" title="{{ $post->name }}" href="{{ $post->url }}">
                {{ $post->name }}
            </a>
        </div>
        <div class="card-program">
            <div class="endtime">
                @if (ThemeHelper::isShowPostMeta('list', 'author', true))
                    {!! Theme::partial('blog.post-meta.author', compact('post')) !!}
                @endif

                <div class="card-button"><a class="btn btn-gray" href="{{ $post->url }}">{{ __('Keep Reading') }}</a></div>
            </div>
        </div>
    </div>
</div>
