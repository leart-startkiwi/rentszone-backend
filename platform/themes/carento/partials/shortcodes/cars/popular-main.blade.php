@php
    $carsChunks = $cars->chunk(ceil($cars->count() / 2));
@endphp
<div class="block-flights wow fadeInUp">
    <div class="box-swiper mt-30">
        @if($carsChunks->isNotEmpty())
            <div class="swiper-container swiper-group-2 swiper-group-journey">
                <div class="swiper-wrapper">
                    @foreach($carsChunks as $carsChunk)
                        <div class="swiper-slide">
                            @foreach($carsChunk as $car)
                                <div class="card-journey-small card-journey-small-listing-3 background-0 d-flex flex-md-row flex-column align-items-center mw-100 position-relative">
                                    <div class="card-image w-100">
                                        <a href="{{ $car->url }}">
                                            {{ RvMedia::image($car->image, $car->name, 'small-rectangle') }}
                                        </a>
                                    </div>
                                    <div class="card-info p-4 mt-0 position-relative end-0 h-100 w-lg-55 rounded-12">
                                        <div class="card-rating position-relative start-0 top-0">
                                            <div class="card-right">
                                                @include(Theme::getThemeNamespace('views.car-rentals.rating'), ['car' => $car, 'cssClass' => 'shadow-none border-0 bg-transparent px-0'])
                                            </div>
                                        </div>
                                        <div class="card-title pb-1"><a class="heading-6 neutral-1000 text-ellipsis" href="{{ $car->url }}" title="{{ $car->name }}">{!! BaseHelper::clean($car->name) !!}</a></div>
                                        <div class="card-program">
                                            @include(Theme::getThemeNamespace('views.car-rentals.car-facilities'), ['car' => $car, 'cssClass' => 'border-0 pb-1'])

                                            <div class="endtime border-top pt-2">
                                                @include(Theme::getThemeNamespace('views.car-rentals.price'), ['car' => $car])
                                                @include(Theme::getThemeNamespace('views.car-rentals.book-now-button'), ['car' => $car])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-xl-medium neutral-500">{{ __('No matching vehicle information found') }}</p>
        @endif
    </div>
</div>
