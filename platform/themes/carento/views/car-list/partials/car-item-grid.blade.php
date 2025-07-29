@php
    $col = BaseHelper::stringify(request()->query('col'));

    if (empty($col)) {
        $col = (int) ($layoutCol ?? 4);
    }

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

<div class="col-lg-{{ $layoutCol }} col-md-6">
    <div class="card-journey-small background-card hover-up">
        <div class="card-image car-image">
            <a href="{{ $carUrl }}">
                {{ RvMedia::image($car->image , $car->name, 'medium-rectangle') }}
            </a>
        </div>
        <div class="card-info p-4 pt-30">
            <div class="card-rating">
                <div class="card-left"></div>
                <div class="card-right">
                    @if($avgReview = $car->avg_review)
                        <span class="rating text-xs-medium rounded-pill">
                           <svg  xmlns="http://www.w3.org/2000/svg"  width="16"  height="16"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" /></svg>

                            <span>
                                {{ $avgReview }}
                            </span>
                            @if($reviews = $car->reviews)
                                <span class="text-xs-medium neutral-500">
                                    ({{ $reviews->count() }} {{ $reviews->count() > 1 ? __('reviews') : __('review') }})
                                </span>
                            @endif
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-title">
                <a class="text-lg-bold neutral-1000 truncate-1-custom" title="{{ $car->name }}" href="{{ $carUrl }}">
                    {{ $car->name }}
                </a>
            </div>
            <div class="card-program">
                @if($car->current_location)
                    <div class="card-location">
                        <p class="text-location text-sm-medium neutral-500 text-location text-sm-medium neutral-500 text-truncate" title="{{ BaseHelper::clean($car->current_address) }}">{{ BaseHelper::clean($car->current_address) }}</p>
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
