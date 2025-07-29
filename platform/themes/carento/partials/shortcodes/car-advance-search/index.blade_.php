@php
    $buttonSearchName = $shortcode->button_search_name;
    $linkNeedHelp = $shortcode->link_need_help;
    $top = $shortcode->top;
    $bottom = $shortcode->bottom;
    $left = $shortcode->left;
    $right = $shortcode->right;
    $url = $shortcode->url;
    $backgroundColor = $shortcode->background_color;

    $variablesStyle = [
        "--box-mt: {$top}px" => $top,
        "--box-mb: {$bottom}px" => $bottom,
        "--box-ml: {$left}px" => $left,
        "--box-mr: {$right}px" => $right,
        "background-color: $backgroundColor" => $backgroundColor,
    ];

    $selectedTabs = explode(',', $shortcode->tabs ?: 'all,new_car,used_car');

    $tabs = collect(['all' => __('All cars'), 'new_car' => __('New cars'), 'used_car' => __('Used cars')])
        ->reject(fn ($tab, $key) => ! in_array($key, $selectedTabs))
        ->sortBy(fn ($tab, $key) => array_search($key, $selectedTabs))
        ->all();

    $isRentalEnabled = get_car_rentals_setting('enabled_car_rental', true);
@endphp
<style>
.dropdown-menu {
    max-height: 300px !important;
    overflow-y: auto !important;
}
</style>

<section class="shortcode-car-advance-search box-section box-search-advance-home10" @style($variablesStyle) id="js-box-search-advance">
    <div class="container">
        <form action="{{ $url }}" method="GET">
            <div class="box-search-advance background-card wow fadeIn">
                <div class="box-top-search">
                    <div class="left-top-search">
                        <input value="{{ $type }}" name="adv_type" hidden/>
                        @php
                            $categoryLinkStyle = [
                                'category-link',
                                'text-sm-bold',
                                'btn-click'
                            ];
                        @endphp

                        @if (count($tabs) > 1)
                            @foreach($tabs as $key => $tab)
                                <a @class([...$categoryLinkStyle, 'active' => $type === $key]) href="#" data-tab="{{ $key }}">{{ $tab }}</a>
                            @endforeach
                        @else
                            <h6>{{ $shortcode->title }}</h6>
                        @endif
                    </div>
                    @if(empty($linkNeedHelp) === false)
                        <div class="right-top-search d-none d-md-flex">
                            <a class="text-sm-medium need-some-help" href="{{ $linkNeedHelp }}">
                                <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M1 12C1 12 0 12 0 11C0 10 1 7 6 7C11 7 12 10 12 11C12 12 11 12 11 12H1ZM6 6C6.79565 6 7.55871 5.68393 8.12132 5.12132C8.68393 4.55871 9 3.79565 9 3C9 2.20435 8.68393 1.44129 8.12132 0.87868C7.55871 0.316071 6.79565 0 6 0C5.20435 0 4.44129 0.316071 3.87868 0.87868C3.31607 1.44129 3 2.20435 3 3C3 3.79565 3.31607 4.55871 3.87868 5.12132C4.44129 5.68393 5.20435 6 6 6Z" fill="currentColor"></path>
                                </svg>

                                {{ __('Need help?') }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="box-bottom-search background-card">
                    @if($isRentalEnabled && $dropOffLocationDefault)
                        <div class="item-search" id="pick-up-location">
                            <label class="text-sm-bold neutral-500">{{ __('Pick Up Location') }}</label>
                            <div class="dropdown">
                                <input value="{{ $pickUpLocationDefault->id }}" name="car_booking_locations[]" type="hidden"/>
                                <button class="btn btn-secondary dropdown-toggle btn-dropdown-search location-search text-ellipsis" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>{{ $pickUpLocationDefault->full_address }}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($locations as $location)
                                        <li>
                                            <a class="dropdown-item" href="#" data-id="{{ $location->id }}">{{ $location->full_address }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="item-search item-search-2" id="drop-off-location">
                            <label class="text-sm-bold neutral-500">{{ __('Drop Off Location') }}</label>
                            <div class="dropdown">
                                <input value="{{ $dropOffLocationDefault->id }}" name="adv_drop_off_location" type="hidden"/>
                                <button class="btn btn-secondary dropdown-toggle btn-dropdown-search location-search text-ellipsis" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>{{ $dropOffLocationDefault->full_address }}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($locations as $location)
                                        <li>
                                            <a class="dropdown-item" href="#" data-id="{{ $location->id }}">{{ $location->full_address }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="item-search item-search-3">
                            <label class="text-sm-bold neutral-500" for="input-start-date">{{ __('Pick Up Date') }}</label>
                            <div class="box-calendar-date">
                                <input class="search-input datepicker" data-format="yyyy-mm-dd" name="start_date" id="input-start-date" type="text" placeholder="" value="{{ $pickUpDateDefault }}" />
                            </div>
                        </div>
                        <div class="item-search bd-none">
                            <label class="text-sm-bold neutral-500" for="input-end-date">{{ __('Return Date') }}</label>
                            <div class="box-calendar-date">
                                <input class="search-input datepicker" name="end_date" id="input-end-date" data-format="yyyy-mm-dd" type="text" placeholder="" value="{{ $returnDateDefault }}" />
                            </div>
                        </div>
                    @else
                        <div class="item-search flex-grow-1">
                            <div class="position-relative">
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.2 15.2L11.7255 11.7255M11.7255 11.7255C12.8837 10.5673 13.6 8.96728 13.6 7.2C13.6 3.66538 10.7346 0.8 7.2 0.8C3.66538 0.8 0.8 3.66538 0.8 7.2C0.8 10.7346 3.66538 13.6 7.2 13.6C8.96728 13.6 10.5673 12.8837 11.7255 11.7255Z" stroke="#6F7C91" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    name="keyword"
                                    id="keyword"
                                    class="form-control ps-5 h-100"
                                    placeholder="{{ __('Enter keywords to search cars...') }}"
                                    value="{{ request()->input('keyword') }}"
                                    style="border-radius: 8px; border: 1px solid #E5E7EB; background: #FFFFFF; min-height: 56px;"
                                >
                            </div>
                        </div>
                    @endif
                    <div class="item-search bd-none d-flex justify-content-end">
                        <button class="btn btn-brand-2 text-nowrap">
                            <svg class="me-2" width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 19L14.6569 14.6569M14.6569 14.6569C16.1046 13.2091 17 11.2091 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 4.58172 17 9 17C11.2091 17 13.2091 16.1046 14.6569 14.6569Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            {!! BaseHelper::clean($buttonSearchName) !!}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
