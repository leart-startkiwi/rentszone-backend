<div class="card-flight card-news background-card">
    <div class="card-image">
        <a href="{{ $post->url }}">
            {{ RvMedia::image($post->image, $post->name, 'medium-square') }}
        </a>
    </div>
    <div class="card-info">
        {!! Theme::partial('blog.post-meta.category-badge', compact('post')) !!}

        <div class="card-title">
            <a class="heading-6 neutral-1000" title="{{ $post->name }}" href="{{ $post->url }}">{{ $post->name }}</a>
        </div>

        {!! Theme::partial('blog.post-meta.index', compact('post')) !!}

        @if ($description = $post->description)
            <div class="card-desc">
                <p class="text-md-medium neutral-500 truncate-2-custom">{!! BaseHelper::clean($description) !!}</p>
            </div>
        @endif

        <div class="card-program">
            <div class="endtime">
                <div class="card-button"><a class="btn btn-gray" href="{{ $post->url }}">{{ __('Keep Reading') }}</a></div>
            </div>
        </div>
    </div>
</div>
