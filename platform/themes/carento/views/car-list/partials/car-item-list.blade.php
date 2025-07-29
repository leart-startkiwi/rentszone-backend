@php
    $transmission = $car->transmission;
    $types = $car->types;
    $make = $car->make;
    $carUrl = $car->url;

    $query = [];

    if ($startDate = BaseHelper::stringify(request()->query('start_date'))) {
        $query['rental_start_date'] = $startDate;
    }

    if ($endDate = BaseHelper::stringify(request()->query('end_date'))) {
        $query['rental_end_date'] = $endDate;
    }

    if ($query) {
        $carUrl = $car->url . '?' . http_build_query($query);
    }
@endphp
<div class="col-xl-12 col-lg-12">
    <div class="card-flight card-hotel card-property background-card border">
        <div class="card-image">
            <a href="{{ $carUrl }}">
                {{ RvMedia::image($car->image , $car->name, 'medium-rectangle') }}
            </a></div>
        <div class="card-info p-md-40 p-3">
            @if($avgReview = $car->avg_review)
            <div class="tour-rate">
                <div class="rate-element">
                    <span class="rating">
                       <svg  xmlns="http://www.w3.org/2000/svg"  width="16"  height="16"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" /></svg>

                        <span>
                            {{ $avgReview }}
                        </span>
                            @if($reviews = $car->reviews)
                                <span class="text-sm-medium neutral-500">
                                    ({{ $reviews->count() ?? 0 }} {{ $reviews->count() > 1 ? __('reviews') : __('review') }})
                                </span>
                            @endif
                    </span>
                </div>
            </div>
            @endif
            <div class="card-title">
                <a class="heading-6 neutral-1000" href="{{ $carUrl }}">
                    {{ $car->name }}
                </a>
            </div>
            <div class="card-program">
                <div class="card-location mb-25">
                    @if($pickAddress = $car->pickupAddress)
                        <p class="text-location text-md-medium neutral-500 text-truncate" title="{{ $pickAddress->detail_address }}">{{ BaseHelper::clean($pickAddress->detail_address) }}</p>
                    @endif
                </div>
                <div class="card-facilities">
                    @if($mileage = $car->mileage)
                        <div class="item-facilities">
                            <p class="room text-md-medium neutral-1000">{{ $mileage }} mileage</p>
                        </div>
                    @endif
                    @if($transmission && $transmission->name)
                        <div class="item-facilities">
                            <p class="size text-md-medium neutral-1000">{{ $transmission->name }}</p>
                        </div>
                    @endif
                    @if($types && $types->name)
                        <div class="item-facilities">
                            <p class="parking text-md-medium neutral-1000">{{ $types->name }}</p>
                        </div>
                    @endif
                    @if($numberOfSeat = $car->number_of_seats)
                        <div class="item-facilities">
                            <p class="bathroom text-md-medium neutral-1000">{{ $numberOfSeat }} {{ $numberOfSeat == 1 ? __('seat') : __('seats') }}</p>
                        </div>
                    @endif
                    @if($make && $make->name)
                        <div class="item-facilities">
                            <p class="pet text-md-medium neutral-1000">{{ $make->name }}</p>
                        </div>
                    @endif
                </div>
                <div class="endtime">
                    @include(Theme::getThemeNamespace('views.car-rentals.price'), ['car' => $car])
                    @include(Theme::getThemeNamespace('views.car-rentals.book-now-button'), ['car' => $car])
                </div>
            </div>
        </div>
    </div>
</div>
