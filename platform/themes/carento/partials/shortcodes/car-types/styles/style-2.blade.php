<section class="shortcode-car-types car-type-style-2 section-box background-body py-96 border-bottom">
    <div class="container">
        <div class="row align-items-end mb-40">
            <div class="col-md-8">
                @if(empty($title) === false)
                    <h2 class="heading-3 shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                @if(empty($subTitle) === false)
                    <p class="text-xl-medium shortcode-subtitle">{!! BaseHelper::clean($subTitle) !!}</p>
                @endif
            </div>
            @if(empty($buttonLabel) === false)
                <div class="col-md-4">
                    <div class="d-flex justify-content-md-end mt-md-0 mt-4">
                        <a class="btn btn-primary" href="{{ $shortcode->button_url }}">
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
                    <div class="col-lg-2 col-md-4 col-sm-4 text-center mb-lg-0 mb-3 wow fadeIn" data-wow-delay="0.1s">
                        <div class="card-popular background-100 hover-up position-relative">
                            <a href="{{ $redirectUrl }}" class="card-image border-0">
                                {{ RvMedia::image($carType->image, $carType->name) }}
                            </a>
                            <div class="card-info">
                                <div class="card-meta position-absolute top-100 start-50 translate-middle ">
                                    <div class="meta-links"><a class="background-0" href="{{ $redirectUrl }}">{!! BaseHelper::clean($carType->cars_count ?: 0) !!} {{ $labelCar }}</a></div>
                                </div>
                            </div>
                        </div>
                        @if(empty($carType->name) === false)
                            <a class="card-title text-lg-bold neutral-1000" href="{{ $redirectUrl }}">{!! BaseHelper::clean($carType->name) !!}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
