@if ($posts->isNotEmpty())
    <div class="sidebar-banner">
        <div class="position-relative">
            @if($title = Arr::get($config, 'title'))
                <p class="text-xl-bold neutral-1000 mb-4">{{ $title }}</p>
            @endif

            @foreach($posts as $post)
                <div class="d-flex align-items-start mb-3">
                <div class="me-3 border rounded-3 overflow-hidden mw-65" style="flex-shrink: 0!important;">
                    <a href="{{ $post->url }}">
                        {{ RvMedia::image($post->image, $post->name, 'thumb', attributes: ['width' => 80]) }}
                    </a>
                </div>
                <div class="position-relative">
                    <a href="{{ $post->url }}" class="text-md-bold neutral-1000 truncate-2-custom">{{ $post->name }}</a>
                    <a href="{{ $post->url }}" class="text-md-bold text-primary">
                        {{ __('View more') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif
