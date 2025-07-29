@use(Theme\Carento\Support\ThemeHelper)
<section class="shortcode-blog-posts blog-list-style-1 section-box box-news background-body">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-9 mb-30 wow fadeInUp">
                @if(!empty($title))
                    <h2 class="heading-3 title-svg shortcode-title mb-15">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                @if(!empty($subtitle))
                    <p class="text-lg-medium text-bold neutral-500">{!! BaseHelper::clean($subtitle) !!}</p>
                @endif
            </div>
            <div class="col-md-3 position-relative mb-30 wow fadeInUp">
                <div class="box-button-slider box-button-slider-team justify-content-end">
                    <div class="swiper-button-prev swiper-button-prev-style-1 swiper-button-prev-2" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-f147def6ba09c37a">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M7.99992 3.33325L3.33325 7.99992M3.33325 7.99992L7.99992 12.6666M3.33325 7.99992H12.6666" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next swiper-button-next-style-1 swiper-button-next-2" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-f147def6ba09c37a">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M7.99992 12.6666L12.6666 7.99992L7.99992 3.33325M12.6666 7.99992L3.33325 7.99992" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-list-news wow fadeInUp mt-5">
            <div class="box-swiper">
                <div class="swiper-container swiper-group-3">
                    <div class="swiper-wrapper">
                        @foreach($posts as $post)
                            @php
                                /** @var \Botble\Blog\Models\Post $post */
                                    $author = $post->author;
                                    $timeStr =  $post->created_at?->format('d M Y') ?: null;
                                    $countComment = $postCommentCount[$post->id] ?? 0;
                            @endphp
                            <div class="swiper-slide pt-2">
                                <div class="card-news background-card hover-up">
                                    @if(!empty($post->image))
                                        <div class="card-image">
                                            <a href="{{ $post->url }}">
                                                {{ RvMedia::image($post->image, $post->name, 'medium-square') }}
                                            </a>
                                        </div>
                                    @endif
                                    <div class="card-info">
                                        {!! Theme::partial('blog.post-meta.floating-category-badge', compact('post')) !!}
                                        <div class="card-meta">
                                            @if(ThemeHelper::isShowPostMeta('detail', 'published_date', true) && $timeStr)
                                                <span class="post-date neutral-1000">{!! BaseHelper::clean($timeStr) !!}</span>
                                            @endif
                                            @if(ThemeHelper::isShowPostMeta('detail', 'reading_time', true) && $post->timeReading)
                                                <span class="post-time neutral-1000">{!! BaseHelper::clean($post->timeReading) !!} {{ __('mins') }}</span>
                                            @endif
                                            @if($countComment)
                                                @php
                                                    $commentLabel = ($countComment > 1 || $countComment == 0) ? __('comments') : __('comment');
                                                @endphp
                                                <span class="post-comment neutral-1000">{{ $countComment }} {{ $commentLabel }}</span>
                                            @endif
                                        </div>
                                        <div class="card-title"><a class="text-xl-bold neutral-1000 text-ellipsis" href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a></div>
                                        <div class="card-program">
                                            <div class="endtime">
                                                @if(ThemeHelper::isShowPostMeta('detail', 'author', true) && $author)
                                                    <div class="card-author">
                                                        {{ RvMedia::image($author->avatarUrl, $author->first_name) }}
                                                        <p class="text-sm-bold neutral-1000">{!! BaseHelper::clean($author->first_name) !!}</p>
                                                    </div>
                                                @endif
                                                @if(!empty($buttonLabel))
                                                    <div class="card-button"><a class="btn btn-gray" href="{{ $post->url }}">{!! BaseHelper::clean($buttonLabel) !!}</a></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
