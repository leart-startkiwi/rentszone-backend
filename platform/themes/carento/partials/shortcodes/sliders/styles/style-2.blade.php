<section class="background-body pt-80 pb-50 shortcode-slider shortcode-slider-style-2">
    <div class="container">
        <div class="box-swiper">
            <div class="swiper-container swiper-group-1 position-relative">
                <div class="swiper-wrapper">
                    @foreach($sliders as $sliderItem)
                        @php
                            $bgImage = $sliderItem->image ? RvMedia::getImageUrl($sliderItem->image) : null;
                            $title = $sliderItem->title;
                            $description = $sliderItem->description;
                            $subtitle = $sliderItem->getMetaData('subtitle', true);
                            $linkLabel = $sliderItem->getMetaData('link_label', true);
                            $link = $sliderItem->link;
                        @endphp
                        @continue(empty($title))

                        <div class="swiper-slide">
                            <div class="item-banner-slide-review d-flex align-items-center rounded-12" @style(["background: url({$bgImage}) lightgray 50%/cover no-repeat" => $bgImage])>
                                <div class="ps-md-5 ps-2 position-relative z-1">
                                    <span class="text-primary text-md-bold">{!! BaseHelper::clean($title) !!}</span>
                                    @if(empty($subtitle) === false)
                                        <h2 class="heading-3 mt-20 mb-20 color-white">{!! BaseHelper::clean($subtitle) !!}</h2>
                                    @endif
                                    @if(empty($description) === false)
                                        <p class="text-lg-medium color-white">{!! BaseHelper::clean($description) !!}</p>
                                    @endif
                                    @if(empty($linkLabel) === false)
                                        <a href="{{ $link }}" class="btn btn-primary mt-30">
                                            {!! BaseHelper::clean($linkLabel) !!}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                                                <path d="M12 19.5L19 12.5L12 5.5M19 12.5L5 12.5" stroke="#101010" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
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
    </div>
</section>
