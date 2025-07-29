@use(Theme\Carento\Support\ThemeHelper)
@use(Botble\Theme\Supports\Youtube)
@php
    $posts = $posts->chunk(4);
    $playIconRaw = ThemeHelper::getPlayVideoIconBase64();
@endphp

<section class="shortcode-blog-posts shortcode-blog-posts-style-3 section-box box-picked background-body"
    @style([
        "--background-icon: url('data:image/svg+xml;base64,$playIconRaw')" => $playIconRaw,
    ])
>
    <div class="container">
        <div class="row align-items-end">
            @if(($title = $shortcode->title) || ($subtitle = $shortcode->subtilte))
                <div class="col-md-9 mb-30 wow fadeInUp">
                    @if($title)
                        <h2 class="heading-3 neutral-1000">{!! BaseHelper::clean($title) !!}</h2>
                    @endif

                    @if($subtitle)
                        <p class="text-lg-medium neutral-500">{!! BaseHelper::clean($subtitle) !!}</p>
                    @endif
                </div>
            @endif

            @if (($linkUrl = $shortcode->link_url) && ($linkLabel = $shortcode->link_label))
                <div class="col-md-3 mb-30 wow fadeInUp">
                    <div class="d-flex justify-content-center justify-content-md-end">
                        <a class="btn btn-primary" href="{{ $linkUrl }}">
                            {!! BaseHelper::clean($linkLabel) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        @if($posts->isNotEmpty())
            <div class="box-videos-small mt-0">
                <div class="bg-video background-2"></div>
                @foreach($posts as $postChunk)
                    @php
                        $firstPost = $postChunk->shift();
                        $youtubeUrl = $firstPost->getMetaData('youtube_video_url', true);
                        $youtubeId = $youtubeUrl ? Youtube::getYoutubeVideoID($youtubeUrl) : null;
                    @endphp
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card-grid-video wow fadeIn">
                                <div class="card-image">
                                    @if ($youtubeId)
                                        <a class="btn-play popup-youtube" href="https://www.youtube.com/watch?v={{ $youtubeId }}"> </a>
                                    @endif
                                    <a href="{{ $firstPost->url }}">
                                        {{ RvMedia::image($firstPost->image, $firstPost->name, 'large-rectangle', attributes: ['class' => 'mr-10']) }}
                                    </a>
                                </div>
                                <div class="card-info">
                                    <a href="{{ $firstPost->url }}">
                                        <h4 class="text-white truncate-2-custom" title="{{ $firstPost->name }}">{{ $firstPost->name }}</h4>
                                    </a>
                                    @if(ThemeHelper::isShowPostMeta('detail', 'published_date', true) && ($timeStr =  $firstPost->created_at?->format('d M Y') ?: null))
                                        <p class="text-md-medium text-white">{!! BaseHelper::clean($timeStr) !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($postChunk->isNotEmpty())
                            <div class="col-lg-5">
                                <div class="list-videos-small">
                                    @foreach($postChunk as $post)
                                        @php
                                            $youtubeUrl = $post->getMetaData('youtube_video_url', true);
                                            $youtubeId = $youtubeUrl ? Youtube::getYoutubeVideoID($youtubeUrl) : null;
                                        @endphp
                                        <div class="item-video-small wow fadeIn" data-wow-delay="0.{{ $loop->index + 1 }}s">
                                            <div class="item-image">
                                                @if ($youtubeId)
                                                    <a class="btn-play-sm popup-youtube" href="https://www.youtube.com/watch?v={{ $youtubeId }}"> </a>
                                                @endif
                                                {{ RvMedia::image($post->image, $post->name, 'small-rectangle', attributes: ['class' => 'mr-10']) }}
                                            </div>
                                            <div class="item-info">
                                                <a class="heading-6 truncate-2-custom" title="{{ $post->name }}" href="{{ $post->url }}">{{ $post->name }}</a>
                                                @if(ThemeHelper::isShowPostMeta('detail', 'published_date', true) && ($timeStr =  $post->created_at?->format('d M Y') ?: null))
                                                    <p class="text-md-medium">{!! BaseHelper::clean($timeStr) !!}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
