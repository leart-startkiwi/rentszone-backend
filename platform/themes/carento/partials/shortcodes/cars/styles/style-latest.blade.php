@php
    $carsChunkSize = $cars->chunk($shortcode->number_rows);
@endphp

<section class="shortcode-cars car-style-lasted section-box box-flights background-body">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-9 wow fadeInUp">
                @if ($title)
                    <h2 class="heading-3 title-svg shortcode-title mb-5">{!! BaseHelper::clean($title) !!}</h2>
                @endif

                @if ($subtitle)
                    <p class="text-lg-medium text-bold shortcode-subtitle">{!! BaseHelper::clean($subtitle) !!}</p>
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
        <div class="block-flights wow fadeInUp">
            <div class="box-swiper mt-30">
                <div class="swiper-container swiper-group-3 swiper-group-journey">
                    <div class="swiper-wrapper">
                        @foreach($carsChunkSize as $cars)
                            @foreach($cars as $car)
                                <div class="swiper-slide">
                                    <div class="card-journey-small background-card hover-up">
                                        <div class="card-image">
                                            <a href="{{ $car->url }}">
                                                {{ RvMedia::image($car->image, $car->name, 'medium-rectangle') }}
                                            </a>
                                        </div>
                                        <div class="card-info">
                                            <div class="card-rating">
                                                <div class="card-left"></div>
                                                <div class="card-right">
                                                    @include(Theme::getThemeNamespace('views.car-rentals.rating'), ['car' => $car])
                                                </div>
                                            </div>
                                            <div class="card-title"><a class="heading-6 neutral-1000 text-ellipsis" href="{{ $car->url }}">{!! BaseHelper::clean($car->name) !!}</a></div>
                                            <div class="card-program">
                                                @if($car->pickupAddress)
                                                    <div class="card-location">
                                                        <p class="text-location text-md-medium neutral-500 text-truncate" title="{{ $car->pickupAddress->full_address }}">{!! BaseHelper::clean($car->pickupAddress->full_address) !!}</p>
                                                    </div>
                                                @endif

                                                @include(Theme::getThemeNamespace('views.car-rentals.car-facilities'), ['car' => $car])

                                                <div class="endtime">
                                                    @include(Theme::getThemeNamespace('views.car-rentals.price'), ['car' => $car])
                                                    @include(Theme::getThemeNamespace('views.car-rentals.book-now-button'), ['car' => $car])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @if(empty($buttonLabel) === false)
            <div class="d-flex justify-content-center">
                <a class="btn btn-primary wow fadeInUp" href="{{ $buttonUrl }}">
                    {!! BaseHelper::clean($buttonLabel) !!}
                    <svg class="svg-icon-arrow" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>
