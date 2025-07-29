@php
    [$carCategories, $carColors, $carTypes, $carTransmissions, $carFuelTypes, $carBookingLocations, $carReviewScores, $carMaxRentalRate, $carAmenities, $advancedTypes] = CarListHelper::dataForFilter(request()->input());

   $layout = BaseHelper::stringify(request()->query('layout'));

    if (!in_array($layout, ['list', 'grid'])) {
        $layout = $defaultLayout ?? 'grid';
    }

    $col = BaseHelper::stringify(request()->query('col'));

    if (empty($col)) {
        $col = (int) ($layoutCol ?? 4);
    }

    if(empty($enableFilter)) {
        $enableFilter = BaseHelper::stringify(request()->query('filter'));

        if (empty($enableFilter)) {
            $enableFilter = 'no';
        }
    }
@endphp

<div class="content-left order-lg-first">
    {!! Form::open(['url' => route('public.ajax.cars'), 'method' => 'GET', 'id' => 'cars-filter-form', 'class' => 'sidebar-filter-mobile__content']) !!}
    <input type="hidden" name="page" data-value="{{ $cars->currentPage() ?: 1 }}" />
    <input type="hidden" name="per_page" />
    <input type="hidden" name="layout" value="{{ $layout }}" />
    <input type="hidden" name="layout_col" value="{{ $col }}" />
    <input type="hidden" name="filter" value="{{ $enableFilter }}" />
    <input type="hidden" name="sort_by" value="{{ BaseHelper::stringify(request()->query('sort_by')) }}" />
    <input type="hidden" name="start_date" value="{{ BaseHelper::stringify(request()->query('start_date')) }}" />
    <input type="hidden" name="end_date" value="{{ BaseHelper::stringify(request()->query('end_date')) }}" />

    @if (CarRentalsHelper::isEnabledFilterCarsBy('locations') && is_plugin_active('location') && (empty($stateId) && empty($cityId)))
        <div class="filter-block mb-30">
            <div class="mb-3 select-style select-style-icon">
                <select
                    class="form-control submit-form-filter form-icons select-active select-location"
                    form="cars-filter-form" id="selectCity"
                    name="location"
                    data-location-type="{{ theme_option('car_location_filter_by', 'state') }}"
                    data-placeholder="{{ BaseHelper::stringify(request()->query('location')) ?: __('Select location') }}"
                >
                </select>
                <img class="icon-location" src="{{ Theme::asset()->url('images/icons/location.svg') }}" alt="icon location" />
                <input type="hidden" name="location" value="{{ BaseHelper::stringify(request()->query('location')) }}">

            </div>
        </div>
    @endif

    @if(CarRentalsHelper::isEnabledFilterCarsBy('vehicle_condition'))
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar vehicle-condition">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Vehicle Condition') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <select
                            name="adv_type"
                            form="cars-filter-form"
                        >
                            @foreach($advancedTypes as $type => $label)
                                @php
                                    $advType = request()->input('adv_type', 'all') ?: 'all';
                                @endphp
                                <option @selected($advType === $type) value="{{ $type }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carMaxRentalRate)
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{ __('Filter Price') }}</h6>
                    <div class="box-collapse scrollFilter">
                        <div class="pt-20">
                            <div class="box-slider-range">
                                <div id="slider-range"
                                     data-current-range="{{ request()->query('rental_rate_to') > 0 ? BaseHelper::stringify(request()->query('rental_rate_to')) : 0 }}"
                                     data-max-rental-rate-range="{{ $carMaxRentalRate }}"
                                     data-currency="{{ get_application_currency()?->title }}"
                                     data-currency-rate="{{ get_application_currency()?->exchange_rate }}"
                                >
                                </div>
                                <div class="box-value-price">
                                    <span class="text-md-medium neutral-1000 rental-rate-from">{{ format_price(0) }}</span>
                                    <span
                                        class="text-md-medium neutral-1000 rental-rate-to">{{ format_price($carMaxRentalRate) }}</span>
                                </div>
                                <input class="input-disabled form-control submit-form-filter value-money"
                                       name="rental_rate_from" type="hidden"
                                       value="{{ request()->query('rental_rate_from') > 0 ? BaseHelper::stringify(request()->query('rental_rate_from')) : 0 }}">
                                <input class="input-disabled form-control submit-form-filter value-money"
                                       name="rental_rate_to" type="hidden"
                                       value="{{ BaseHelper::stringify(request()->query('rental_rate_to', $carMaxRentalRate)) }}"
                                       data-default-value="{{ $carMaxRentalRate }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carCategories->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Car categories') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carCategories as $carCategory)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carCategory->id }}"
                                            name="car_categories[]"
                                            id="check-car-category-{{ $carCategory->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carCategory->id, (array) request()->input('car_categories', [])))
                                        >
                                        <span class="text-small truncate-1-custom">{{ $carCategory->name }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carCategory->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carCategories->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">
                                    {{ __('See less') }}
                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>


                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carColors->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Car colors') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carColors as $carColor)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carColor->id }}"
                                            name="car_colors[]"
                                            id="check-car-color-{{ $carColor->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carColor->id, (array) request()->input('car_colors', [])))
                                        >
                                        <span class="text-small truncate-1-custom">{{ $carColor->name }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carColor->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carColors->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">
                                    {{ __('See less') }}
                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>


                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carTypes->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Car types') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carTypes as $carType)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carType->id }}"
                                            name="car_types[]"
                                            id="check-car-type-{{ $carType->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carType->id, (array) request()->input('car_types', [])))
                                        >
                                        <span class="text-small truncate-1-custom">{{ $carType->name }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carType->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carTypes->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">
                                    {{ __('See less') }}
                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>


                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carTransmissions->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Car transmissions') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carTransmissions as $carTransmission)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carTransmission->id }}"
                                            name="car_transmissions[]"
                                            id="check-car-transmission-{{ $carTransmission->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carTransmission->id, (array) request()->input('car_transmissions', [])))
                                        >
                                        <span class="text-small truncate-1-custom">{{ $carTransmission->name }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carTransmission->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carTransmissions->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">
                                    {{ __('See less') }}
                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carAmenities->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Car Amenities') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carAmenities as $carAmenity)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carAmenity->id }}"
                                            name="car_amenities[]"
                                            id="check-car-amenity-{{ $carAmenity->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carAmenity->id, (array) request()->input('car_amenities', [])))
                                        >
                                        <span class="text-small">{{ $carAmenity->name }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carAmenity->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carAmenity->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">

                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>


                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carFuelTypes->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{  __('Fuel types') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carFuelTypes as $carFuelType)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carFuelType->id }}"
                                            name="car_fuel_types[]"
                                            id="check-car-fuel-type-{{ $carFuelType->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carFuelType->id, (array) request()->input('car_fuel_types', [])))
                                        >
                                        <span class="text-small truncate-1-custom">{{ $carFuelType->name }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carFuelType->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carFuelTypes->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">
                                    {{ __('See less') }}
                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>


                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carReviewScores->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{ __('Review Score') }}</h6>
                    <div class="box-collapse scrollFilter">
                        <ul class="list-filter-checkbox">
                            @foreach($carReviewScores as $carReviewScore)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carReviewScore->star }}"
                                            name="car_review_scores[]"
                                            id="check-car-review-score-{{ $carReviewScore->star }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carReviewScore->star, (array) request()->input('car_review_scores', [])))
                                        >
                                        @include(Theme::getThemeNamespace('views.car-list.partials.review-scores'), ['score' => $carReviewScore->star])
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carReviewScore->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($carBookingLocations->isNotEmpty())
        <div class="sidebar-left border-1 background-body">
            <div class="box-filters-sidebar">
                <div class="block-filter border-1">
                    <h6 class="text-lg-bold item-collapse neutral-1000">{{ __('Booking Location') }}</h6>
                    <div class="box-collapse scrollFilter car-filter-checkbox">
                        <ul class="list-filter-checkbox">
                            @foreach($carBookingLocations as $carBookingLocation)
                                <li>
                                    <label class="cb-container">
                                        <input
                                            type="checkbox"
                                            class="submit-form-filter"
                                            value="{{ $carBookingLocation->id }}"
                                            name="car_booking_locations[]"
                                            id="check-car-booking-location-{{ $carBookingLocation->id }}"
                                            form="cars-filter-form"
                                            @checked(in_array($carBookingLocation->id, (array) request()->input('car_booking_locations', [])))
                                        >
                                        <span class="text-small truncate-1-custom">{{ $carBookingLocation->detail_address }}</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="number-item">{{ $carBookingLocation->cars_count ?: 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if($carBookingLocations->count() > 5)
                            <div class="box-see-more mt-20 mb-25">
                                <span class="link-see-more link-see-more-js">
                                    {{ __('See more') }}
                                    <svg width="8" height="6" viewbox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.89553 1.02367C7.75114 0.870518 7.50961 0.864815 7.35723 1.00881L3.9998 4.18946L0.642774 1.00883C0.490387 0.86444 0.249236 0.870534 0.104474 1.02369C-0.0402885 1.17645 -0.0338199 1.4176 0.118958 1.56236L3.73809 4.99102C3.81123 5.06036 3.90571 5.0954 3.9998 5.0954C4.0939 5.0954 4.18875 5.06036 4.26191 4.99102L7.88104 1.56236C8.03382 1.41758 8.04029 1.17645 7.89553 1.02367Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <span class="link-see-more link-see-less">
                                    {{ __('See less') }}
                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_581_25679)">
                                        <path
                                            d="M0.130593 7.47047C0.311077 7.66191 0.612984 7.66904 0.803468 7.48905L5.00024 3.51324L9.19653 7.48903C9.38702 7.66951 9.68846 7.66189 9.86941 7.47045C10.0504 7.2795 10.0423 6.97806 9.8513 6.79711L5.32739 2.51129C5.23596 2.42461 5.11786 2.38081 5.00024 2.38081C4.88263 2.38081 4.76406 2.42461 4.67262 2.51129L0.148698 6.79711C-0.0422741 6.97808 -0.0503599 7.2795 0.130593 7.47047Z"
                                            fill="black" />
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_581_25679">
                                        <rect width="10" height="10" fill="white" transform="matrix(-1 0 0 -1 10 10)" />
                                        </clipPath>
                                        </defs>
                                    </svg>


                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
