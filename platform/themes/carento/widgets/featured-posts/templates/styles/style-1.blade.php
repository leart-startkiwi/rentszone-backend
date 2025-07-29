@use(Theme\Carento\Support\ThemeHelper)

@php
    $postChunks = $posts->chunk(5);
@endphp

<div class="section-box box-posts-grid-2 background-100">
    <div class="container">
        <div class="text-center  mb-40">
            @if ($title = Arr::get($config, 'title'))
                <h3 class="my-3 neutral-1000">{{ $title }}</h3>
            @endif
        </div>

        @foreach($postChunks as $posts)
            @continue($posts->isEmpty())
            @php
                $firstPost = $posts->first();
            @endphp
            <div class="row">
                <div class="col-lg-7">
                    <div class="card-blog">
                        <div class="card-image">
                            {{ RvMedia::image($firstPost->image, $firstPost->name) }}
                        </div>
                        <div class="card-info">
                            <div class="card-info-blog">
                                @if($category = $firstPost->firstCategory)
                                    <a class="btn btn-label-tag" href="{{ $category->url }}">{{ $category->name }}</a>
                                @endif

                                <a class="card-title heading-5 text-white" href="{{ $firstPost->url }}">{{ $firstPost->name }}</a>
                                <div class="card-meta-user">
                                    @if (ThemeHelper::isShowPostMeta('list', 'author', true) && ($author = $firstPost->author))
                                        <div class="box-author-small">
                                            {{ RvMedia::image($author->avatar_url, $author->name, 'thumb', attributes: ['width' => 32, 'height' => 32]) }}
                                            <p class="text-sm-bold">{{ __('By :author', ['author' => $author->name]) }}</p>
                                        </div>
                                    @endif

                                    @if (ThemeHelper::isShowPostMeta('list', 'published_date', true))
                                        <div class="date-post">
                                            <p class="text-sm-medium">{{ Theme::formatDate($firstPost->created_at) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <ul class="list-posts list-posts-md">
                        @foreach($posts->skip(1) as $post)
                            <li>
                                <div class="card-post align-items-start">
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
        @endforeach

        @if ($categories->isNotEmpty())
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 pt-55">
                <span class="text-md-bold neutral-1000"> {{ __('CATEGORY:') }} </span>
                @foreach($categories as $category)
                    <a href="{{ $category->url }}" class="btn btn-white px-3 py-2">{{ $category->name }}</a>
                @endforeach
            </div>
        @endif
    </div>
</div>
