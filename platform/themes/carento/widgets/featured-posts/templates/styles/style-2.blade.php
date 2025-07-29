@use(Theme\Carento\Support\ThemeHelper)

<section class="box-section background-body pt-80">
    <div class="container">
        <div class="text-center  mb-40">
            @if ($title = Arr::get($config, 'title'))
                <h3 class="my-3 neutral-1000">{{ $title }}</h3>
            @endif
        </div>
        <div class="box-swiper">
            <div class="swiper-container swiper-group-1 position-relative">
                <div class="swiper-wrapper">
                    @foreach($posts as $post)
                        @php
                            $image = $post->image ? RvMedia::getImageUrl($post->image) : null;
                        @endphp
                        <div class="swiper-slide">
                            <div class="item-banner-slide-review d-flex align-items-center rounded-12"
                                @style(["background-image: url('$image')" => $image])
                            >
                                <div class="ps-md-5 ps-2 position-relative z-1">
                                    @if($category = $post->firstCategory)
                                        <a href="{{ $category->url }}"><span class="text-primary text-md-bold">{{ $category->name }}</span></a>
                                    @endif

                                    <h3 class="mt-20 mb-20 color-white">
                                        {{ $post->name }}
                                    </h3>
                                    <div class="card-meta-user">
                                        @if (ThemeHelper::isShowPostMeta('list', 'author', true) && ($author = $post->author))
                                            <div class="box-author-small">
                                                {{ RvMedia::image($author->avatar_url, $author->name, 'thumb', attributes: ['width' => 32, 'height' => 32]) }}
                                                <p class="text-sm-bold">{{ __('By :author', ['author' => $author->name]) }}</p>
                                            </div>
                                        @endif

                                        @if (ThemeHelper::isShowPostMeta('list', 'published_date', true))
                                            <div class="date-post">
                                                <p class="text-sm-medium">{{ Theme::formatDate($post->created_at) }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($description = $post->description)
                                        <p class="text-lg-medium color-white mt-3 truncate-2-custom">
                                            {!! BaseHelper::clean($description) !!}
                                        </p>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="position-absolute end-0 bottom-0 p-40">
                    <div class="box-button-slider box-button-slider-team justify-content-end">
                        <div class="swiper-button-prev swiper-button-prev-style-1 swiper-button-prev-2" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-9c1b729b91027a37b">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.99992 3.33325L3.33325 7.99992M3.33325 7.99992L7.99992 12.6666M3.33325 7.99992H12.6666" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div class="swiper-button-next swiper-button-next-style-1 swiper-button-next-2" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-9c1b729b91027a37b">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.99992 12.6666L12.6666 7.99992L7.99992 3.33325M12.6666 7.99992L3.33325 7.99992" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($categories->isNotEmpty())
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 pt-55 pb-60">
                <span class="text-md-bold neutral-1000"> {{ __('CATEGORY:') }} </span>
                @foreach($categories as $category)
                    <a href="{{ $category->url }}" class="btn btn-white px-3 py-2">{{ $category->name }}</a>
                @endforeach
            </div>
        @endif
    </div>
</section>
