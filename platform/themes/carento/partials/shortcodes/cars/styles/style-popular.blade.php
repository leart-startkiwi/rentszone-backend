@php
    $priceRanges = generate_car_rental_price_ranges();
@endphp
<section class="shortcode-cars car-style-popular section-box box-flights background-body">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-6 mb-30 text-center text-lg-start">
                @if(empty($title) === false)
                    <h2 class="shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                @if(empty($subtitle) === false)
                    <p class="text-xl-medium shortcode-subtitle">{!! BaseHelper::clean($subtitle) !!}</p>
                @endif
            </div>
            <div class="col-lg-6 mb-30">
                <div class="d-flex align-items-center justify-content-center justify-content-lg-end popular-categories">
                    <input hidden id="popular-vehicle-limit" name="popular_vehicle_limit" value="{{ $shortcode->limit }}"/>
                    <input hidden id="popular-vehicle-url" name="popular-vehicle-url" value="{{ route('public.ajax.search-popular-vehicles') }}"/>
                    @if(isset($filterTypes['category']))
                        <div class="dropdown dropdown-filter" id="filter-popular-category">
                            <input hidden name="popular-vehicle-category"/>
                            <button class="btn btn-dropdown dropdown-toggle m-0" id="dropdownCategory" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span>Categories</span></button>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownCategory">
                                @foreach($carTypes as $type)
                                    <li>
                                        <a class="dropdown-item" data-value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(isset($filterTypes['fuel_type']))
                        <div class="dropdown dropdown-filter" id="filter-popular-fuel">
                            <input hidden name="popular-vehicle-fuel"/>
                            <button class="btn btn-dropdown dropdown-toggle m-0" id="dropdownCategory" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span>Fuel Type</span></button>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownCategory">
                                @foreach($fuelTypes as $fuelType)
                                    <li>
                                        <a class="dropdown-item" data-value="{{ $fuelType->id }}">
                                            {{ $fuelType->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(isset($filterTypes['order']))
                        <div class="dropdown dropdown-filter" id="filter-popular-order">
                            <input hidden name="popular-vehicle-order"/>
                            <button class="btn btn-dropdown dropdown-toggle m-0" id="dropdownCategory" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span>{{ __('Review / Rating') }}</span></button>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownCategory">
                                <li><a class="dropdown-item" href="#" data-value="asc">{{ __('Newest') }}</a></li>
                                <li><a class="dropdown-item" href="#" data-value="desc">{{ __('Oldest') }}</a></li>
                            </ul>
                        </div>
                    @endif
                    @if(isset($filterTypes['price_range']) && count($priceRanges))
                        <div class="dropdown dropdown-filter" id="filter-popular-price">
                            <input hidden name="popular-vehicle-price"/>
                            <button class="btn btn-dropdown dropdown-toggle m-0" id="dropdownCategory" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span>{{ __('Price range') }}</span></button>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownCategory">
                                @foreach($priceRanges as $key => $range)
                                    <li><a class="dropdown-item" href="#" data-value="{{ $key }}">{{ $range }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div id="content-popular-vehicles">
            {!! Theme::partial('shortcodes.cars.popular-main', compact('cars')) !!}
        </div>
        @if(empty($buttonLabel) === false)
            <div id="popular-vehicle-load-more" class="d-flex justify-content-center">
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
