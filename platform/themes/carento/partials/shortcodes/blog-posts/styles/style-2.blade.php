@use(Theme\Carento\Support\ThemeHelper)
<section class="shortcode-blog-posts blog-list-style-2 section-box background-body pb-85">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-9 mb-30 wow fadeInUp">
                @if(!empty($title))
                    <h2 class="heading-3 title-svg shortcode-title mb-15 wow fadeInUp">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                    @if(!empty($subtitle))
                        <p class="text-lg-medium text-bold shortcode-subtitle wow fadeInUp">{!! BaseHelper::clean($subtitle) !!}</p>
                    @endif
            </div>
            <div class="col-md-3 position-relative mb-30 wow fadeInUp">
                <div class="box-button-slider box-button-slider-team justify-content-end">
                    <div class="swiper-button-prev swiper-button-prev-style-1 swiper-button-prev-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 16 16" fill="none">
                            <path d="M7.99992 3.33325L3.33325 7.99992M3.33325 7.99992L7.99992 12.6666M3.33325 7.99992H12.6666" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next swiper-button-next-style-1 swiper-button-next-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 16 16" fill="none">
                            <path d="M7.99992 12.6666L12.6666 7.99992L7.99992 3.33325M12.6666 7.99992L3.33325 7.99992" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-list-news wow fadeInUp mt-5">
            <div class="box-swiper">
                <div class="swiper-container swiper-group-2">
                    <div class="swiper-wrapper">
                        @foreach($posts->chunk(2) as $postChunkValues)
                            <div class="swiper-slide pb-4 pt-2">
                                @foreach($postChunkValues as $post)
                                    @php
                                        /** @var \Botble\Blog\Models\Post $post */
                                            $author = $post->author;
                                            $timeStr =  $post->created_at?->format('d M Y') ?: null;
                                            $countComment = $postCommentCount[$post->id] ?? 0;
                                    @endphp
                                    <div class="card-news background-card hover-up d-md-flex mb-4">
                                        <div class="card-image">
                                            <a href="{{ $post->url }}">
                                                {{ RvMedia::image($post->image, $post->name, 'small-rectangle-vertical', attributes: ['class' => 'w-100']) }}
                                            </a>
                                        </div>
                                        <div class="card-info mt-0 w-68 px-4">
                                            <div class="card-title mb-2">
                                                <a class="text-xl-bold neutral-1000" href="{{ $post->url }}">
                                                    <h6>{!! BaseHelper::clean($post->name) !!}</h6>
                                                </a>
                                            </div>
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
                                            <p class="text-md-medium neutral-500 tex text-ellipsis">{!! BaseHelper::clean($post->description) !!}</p>
                                            <div class="card-program">
                                                <div class="endtime mt-4">
                                                    <div class="card-button"><a class="btn btn-gray" href="{{ $post->url }}">{!! BaseHelper::clean($buttonLabel) !!}</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
