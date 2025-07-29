<section class="section-box box-banner-home7 background-body shortcode-slider shortcode-slider-style-1">
    <div class="container-banner-home7 position-relative">
        <div class="box-swiper">
            <div class="swiper-container swiper-group-1">
                <div class="swiper-wrapper">
                    @foreach($sliders as $sliderItem)
                        @php
                            $bgImage = $sliderItem->image ? RvMedia::getImageUrl($sliderItem->image) : null;
                            $labelTop = $sliderItem->getMetaData('label_top', true);
                        @endphp
                        @continue(empty($sliderItem->title))
                        <div class="swiper-slide">
                            <div class="item-banner-slide" @style(["background: url({$bgImage}) lightgray 50%/cover no-repeat" => $bgImage])>
                                <div class="container">
                                    @if($labelTop)
                                        <span class="btn background-brand-2 px-3 py-3 rounded-12 text-sm-bold text-dark">{!! BaseHelper::clean($labelTop) !!}</span>
                                    @endif
                                    <h1 class="mt-20 mb-20 color-white">{!! BaseHelper::clean($sliderItem->title) !!}</h1>
                                    <h6 class="color-white heading-6-medium pb-lg-0 pb-4">
                                        {!! BaseHelper::clean($sliderItem->description) !!}
                                    </h6>
                                    @if($footerOnTop)
                                        <span class="d-lg-none">{!! BaseHelper::clean($footerOnTop) !!}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="container-search-advance">
        <div class="container">
            @if($contentOnTop)
                {!! BaseHelper::clean($contentOnTop) !!}
            @endif
            <div class="align-items-center justify-content-between pt-40 d-none d-lg-flex">
                @if($footerOnTop)
                    {!! BaseHelper::clean($footerOnTop) !!}
                @endif
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
</section>
