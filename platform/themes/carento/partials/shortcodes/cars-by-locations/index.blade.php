@php
    $buttonName = $shortcode->button_label;
    $buttonUrl = $shortcode->button_url;
    $title = $shortcode->title;
    $mainContent = $shortcode->main_content;
    $redirectUrl = $shortcode->redirect_url ?: '';
@endphp

<section class="shortcode-cars-by-locations section-box box-properties-area pt-96 pb-50 background-body">
    <div class="container">
        <div class="row align-items-end mb-40">
            <div class="col-md-8">
                @if($title)
                    <h2 class="heading-3 neutral-1000">{!! BaseHelper::clean($title) !!}</h2>
                @endif

                @if($mainContent)
                    <p class="text-lg-medium neutral-500">{!! BaseHelper::clean($mainContent) !!}</p>
                @endif
            </div>
            @if($buttonName && $buttonUrl)
                <div class="col-md-4 mt-md-0 mt-4">
                    <div class="d-flex justify-content-md-end justify-content-center">
                        <a class="btn btn-primary" href="{{ $buttonUrl }}">
                            {!! BaseHelper::clean($buttonName) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="box-list-featured">
            <div class="box-swiper mt-0">
                <div class="swiper-container swiper-group-4 swiper-group-journey">
                    <div class="swiper-wrapper">
                        @foreach($locations as $location)
                            @php
                                $name = $location['name'] ?? '';
                                $locationIds = $location['location_ids'] ?? [];
                                $image = $location['image'] ?? '';
                                $countCar = $location['count_cars'] ?? 0;
                                $countCarLabel = ($countCar == 0 || $countCar > 1) ? __('Vehicles') : __('Vehicle');
                                $carAddressIds = $location['car_address_ids'] ?? [];
                            @endphp

                            @continue(! $name)
                            <div class="swiper-slide">
                            <div class="card-spot background-card wow fadeInDown">
                                <div class="card-image">
                                    <a href="{{ $link = route('public.cars') . '?' . http_build_query(['location' => $locationIds]) }}">
                                        {{ RvMedia::image($image, $name, 'small-rectangle-vertical') }}
                                    </a>
                                </div>
                                <div class="card-info background-card">
                                    <div class="card-left">
                                        <div class="card-title"><a class="text-lg-bold neutral-1000" href="{{ $link }}">{!! BaseHelper::clean($name) !!}</a></div>
                                        <div class="card-desc"><a class="text-sm neutral-500" href="#">{{ $countCar }} {{ $countCarLabel }}</a></div>
                                    </div>
                                    <div class="card-right">
                                        <div class="card-button">
                                            <a href="{{ $link }}">
                                                <svg width="10" height="10" viewbox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.00011 9.08347L9.08347 5.00011L5.00011 0.916748M9.08347 5.00011L0.916748 5.00011" stroke="" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a>
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
