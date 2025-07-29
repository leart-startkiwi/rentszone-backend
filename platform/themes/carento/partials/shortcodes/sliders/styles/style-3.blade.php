@if ($sliders->isNotEmpty())
    <section class="box-section block-banner-home3 position-relative shortcode-slider shortcode-slider-style-2">
        <div class="container-banner-home3 position-relative">
            <div class="box-swiper">
                <div class="swiper-container swiper-group-1">
                    <div class="swiper-wrapper">
                        @foreach($sliders as $key => $slider)
                            @php
                                $bgImage = $slider->image ? RvMedia::getImageUrl($slider->image) : null;
                            @endphp
                            <div class="swiper-slide">
                                <div class="item-banner-slide banner-{{ $key + 1 }}" @style(["background: url({$bgImage}) lightgray 50%/cover no-repeat !important;" => $bgImage])>
                                    <div class="container text-center position-relative z-1">
                                        @if ($subtitle = $slider->getMetaData('subtitle', true))
                                            <span
                                                class="btn background-brand-2 px-3 py-3 rounded-12 text-sm-bold text-dark">
                                            {!! BaseHelper::clean($subtitle) !!}</span>
                                        @endif

                                        @if ($title = $slider->title)
                                            <h1 class="mt-20 mb-20 color-white">{!! BaseHelper::clean($title) !!}</h1>
                                        @endif

                                        @if ($description = $slider->description)
                                            <h6 class="color-white heading-6-medium">
                                                {!! BaseHelper::clean($description) !!}
                                            </h6>
                                        @endif

                                        @if ($keywords = $slider->getMetaData('keywords', true))
                                            <div
                                                class="d-flex align-items-center justify-content-center pt-60 flex-wrap">
                                                <span
                                                    class="text-sm-bold text-white"> {{ __('Popular Searches') }}: </span>
                                                &nbsp;
                                                @foreach($keywords as $keywordKey =>$keyword)
                                                    <a href="{{ $keyword['link'] }}"
                                                       class="text-white text-decoration-underline">
                                                        {{ BaseHelper::clean($keyword['name']) }}
                                                    </a>@if($keywordKey != count($keywords) - 1)<span class="text-white">,&nbsp;</span>@endif
                                                @endforeach
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-none d-md-block">
                        <div class="swiper-button-prev swiper-button-prev-style-1 swiper-button-prev-2" tabindex="0"
                             role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-9c1b729b91027a37b">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                 fill="none">
                                <path
                                    d="M7.99992 3.33325L3.33325 7.99992M3.33325 7.99992L7.99992 12.6666M3.33325 7.99992H12.6666"
                                    stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div class="swiper-button-next swiper-button-next-style-1 swiper-button-next-2" tabindex="0"
                             role="button" aria-label="Next slide" aria-controls="swiper-wrapper-9c1b729b91027a37b">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                 fill="none">
                                <path
                                    d="M7.99992 12.6666L12.6666 7.99992L7.99992 3.33325M12.6666 7.99992L3.33325 7.99992"
                                    stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
