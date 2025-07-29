@use(Theme\Carento\Support\ThemeHelper)

@if ($posts->isNotEmpty())
    <div class="box-sidebar-border">
        @if($title = Arr::get($config, 'title'))
            <div class="box-head-sidebar">
                <p class="text-xl-bold neutral-1000">{{ $title }}</p>
            </div>
        @endif

        <div class="box-content-sidebar">
            <ul class="list-posts">
                @foreach($posts as $post)
                    <li>
                        <div class="card-post">
                            <div class="card-image">
                                <a href="{{ $post->url }}">
                                    {{ RvMedia::image($post->image, $post->name, 'thumb') }}
                                </a>
                            </div>
                            <div class="card-info">
                                <a class="text-md-bold neutral-1000 truncate-2-custom" title="{{ $post->name }}" href="{{ $post->url }}">{{ $post->name }}</a>

                                @if (ThemeHelper::isShowPostMeta('list', 'published_date', true))
                                    <p class="text-sm-medium post-date neutral-500">{{ Theme::formatDate($post->created_at) }}</p>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
