<section class="shortcode-cars car-style-feature section-box box-flights background-body">
    <div class="container">
        <div class="row align-items-end mb-10">
            <div class="col-md-8">
                @if(!empty($title))
                    <h2 class="heading-3 shortcode-title wow fadeInUp">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                @if(!empty($subtitle))
                    <p class="text-lg-medium shortcode-subtitle wow fadeInUp">{!! BaseHelper::clean($subtitle) !!}</p>
                @endif
            </div>
            @if(empty($buttonLabel) === false)
                <div class="col-md-4 mt-md-0 mt-4">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-primary wow fadeInUp" href="{{ $buttonUrl }}">
                            {!! BaseHelper::clean($buttonLabel) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="row pt-30">
            @foreach($cars as $car)
                <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.3s">
                    <div class="card-journey-small background-card hover-up">
                        <div class="card-image">
                            <a href="{{ $car->url }}">
                                {{ RvMedia::image($car->image, $car->name, 'small-rectangle') }}
                            </a>
                        </div>
                        <div class="card-info p-4 pt-30">
                            <div class="card-rating">
                                <div class="card-left"></div>
                                <div class="card-right">
                                    @include(Theme::getThemeNamespace('views.car-rentals.rating'), ['car' => $car, 'cssClass' => 'text-xs-medium py-1 rounded-pill'])
                                </div>
                            </div>
                            <div class="card-title"><a class="text-lg-bold neutral-1000 text-ellipsis" href="{{ $car->url }}">{{ $car->name }}</a></div>
                            <div class="card-program">
                                @if($car->pickupAddress)
                                    <div class="card-location">
                                        <p class="text-location text-sm-medium neutral-500 text-truncate" title="{{ $car->pickupAddress->full_address }}">
                                            {{ $car->pickupAddress->full_address }}
                                        </p>
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
        </div>
    </div>
</section>
