<section class="shortcode-car-types car-type-style-1 section-box background-body py-96">
    <div class="container">
        <div class="row align-items-end mb-40">
            <div class="col-md-8">
                @if(empty($title) === false)
                    <h2 class="heading-3 shortcode-title wow fadeInUp">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                @if(empty($subTitle) === false)
                    <p class="text-xl-medium shortcode-subtitle wow fadeInUp">{!! BaseHelper::clean($subTitle) !!}</p>
                @endif
            </div>

            @if(empty($buttonLabel) === false)
                <div class="col-md-4">
                    <div class="d-flex justify-content-md-end mt-md-0 mt-4">
                        <a class="btn btn-primary wow fadeInUp" href="{{ $shortcode->button_url }}">
                            {!! BaseHelper::clean($buttonLabel) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="box-list-populars">
            <div class="row">
                @foreach($carTypes as $carType)
                    @php
                        $labelCar = ($carType->cars_count > 1 || $carType->cars_count == 0) ? __('Vehicles') : __('Vehicle');
                        $redirectUrl = "{$shortcode->redirect_url}?car_types[]={$carType->id}"
                    @endphp
                    <div class="col-lg-3 col-sm-6">
                        <div class="card-popular background-card hover-up wow fadeIn" data-wow-delay="0.1s">
                            @if(!empty($carType->image))
                                <div class="card-image">
                                    <a class="card-title" href="{{ $redirectUrl }}">
                                        {{ RvMedia::image($carType->image, $carType->name) }}
                                    </a>
                                </div>
                            @endif
                            <div class="card-info">
                                @if(!empty($carType->name))
                                    <a class="card-title" href="{{ $redirectUrl  }}">{!! BaseHelper::clean($carType->name) !!}</a>
                                @endif
                                <div class="card-meta">
                                    <div class="meta-links"><a href="{{ $redirectUrl  }}">{!! BaseHelper::clean($carType->cars_count ?: 0) !!} {{ $labelCar }}</a></div>
                                    <div class="card-button">
                                        <a href="{{ $redirectUrl  }}">
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
</section>
