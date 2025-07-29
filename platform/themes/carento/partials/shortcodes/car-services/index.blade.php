<section class="shortcode-car-services section-box background-body py-96">
    <div class="container">
        <div class="row align-items-end">
            @if ($title = $shortcode->title)
                <div class="col-lg-7">
                    <h2 class="heading-3 shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                </div>
            @endif

            @if ($description = $shortcode->description)
                <div class="col-lg-5">
                    <p class="text-lg-medium shortcode-subtitle">{!! BaseHelper::clean($description) !!}</p>
                </div>
            @endif

        </div>
        <div class="row mt-50">
            @foreach($services as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="card-news background-card hover-up mb-24">
                        <div class="card-image">
                            {!! RvMedia::image($service->image, $service->name, 'medium-rectangle') !!}
                        </div>
                        <div class="card-info">
                            <div class="card-title mb-3">
                                <a class="text-xl-bold neutral-1000" href="{{ $service->url }}">{{ $service->name }}</a>

                                @if ($description = $service->description)
                                    <p class="text-md-medium neutral-500 mt-2 truncate-3-custom">{!! BaseHelper::clean($description) !!}</p>
                                @endif
                            </div>
                            <div class="card-program">
                                <div class="endtime">
                                    <div class="card-button"><a class="btn btn-primary2" href="{{ $service->url }}">{{ __('View Details') }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
