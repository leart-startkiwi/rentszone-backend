@use(Botble\Theme\Supports\Youtube;)

@php
    Theme::set('breadcrumbs', false);
    Theme::layout('full-width');

    $youtubeUrl = $car->getMetaData('youtube_video_url', true);

    $youtubeId = $youtubeUrl ? Youtube::getYoutubeVideoID($youtubeUrl) : null;

    $images = $car->getImages();
@endphp

<div class="car-detail-page">
    {!! Theme::partial('cars.car-detail.includes.breadcrumbs', compact('car')) !!}

    <div class="box-section box-content-tour-detail background-body">
        <div class="container">
            <div class="tour-header">
                @if ($car->reviews_count)
                    <div class="tour-rate">
                        <div class="rate-element">
                            @include(Theme::getThemeNamespace('views.car-rentals.rating'), ['car' => $car])
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tour-title-main">
                            <h4 class="neutral-1000">{{ $car->name }}</h4>
                        </div>
                    </div>
                </div>
                <div class="tour-metas">
                    <div class="tour-meta-left">
                        @if($car->current_location)
                            <p class="text-md-medium neutral-1000 mr-20 tour-location">
                                <svg class="invert" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M7.99967 0C4.80452 0 2.20508 2.59944 2.20508 5.79456C2.20508 9.75981 7.39067 15.581 7.61145 15.8269C7.81883 16.0579 8.18089 16.0575 8.38789 15.8269C8.60867 15.581 13.7943 9.75981 13.7943 5.79456C13.7942 2.59944 11.1948 0 7.99967 0ZM7.99967 8.70997C6.39211 8.70997 5.0843 7.40212 5.0843 5.79456C5.0843 4.187 6.39214 2.87919 7.99967 2.87919C9.6072 2.87919 10.915 4.18703 10.915 5.79459C10.915 7.40216 9.6072 8.70997 7.99967 8.70997Z" fill="#101010" />
                                </svg>
                                {!! BaseHelper::clean($car->current_location) !!}
                            </p>
                            <a class="text-md-medium neutral-1000 mr-30" href="https://maps.google.com/maps?q={{ addslashes($car->current_location) }}">{{ __('Show on map') }}</a>
                        @endif
                    </div>
                    <div>
                        @if ($socials = \Botble\Theme\Supports\ThemeSupport::getSocialSharingButtons($car->url, SeoHelper::getDescription()))
                            <div class="dropdown car-detail-share">
                                <button class="btn btn-share btn btn-outline-secondary dropdown-toggle" type="button" id="shareDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <svg width="16" height="18" viewbox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 11.5332C12.012 11.5332 11.1413 12.0193 10.5944 12.7584L5.86633 10.3374C5.94483 10.0698 6 9.79249 6 9.49989C6 9.10302 5.91863 8.72572 5.77807 8.37869L10.7262 5.40109C11.2769 6.04735 12.0863 6.46655 13 6.46655C14.6543 6.46655 16 5.12085 16 3.46655C16 1.81225 14.6543 0.466553 13 0.466553C11.3457 0.466553 10 1.81225 10 3.46655C10 3.84779 10.0785 4.20942 10.2087 4.54515L5.24583 7.53149C4.69563 6.90442 3.8979 6.49989 3 6.49989C1.3457 6.49989 0 7.84559 0 9.49989C0 11.1542 1.3457 12.4999 3 12.4999C4.00433 12.4999 4.8897 11.9996 5.4345 11.2397L10.147 13.6529C10.0602 13.9331 10 14.2249 10 14.5332C10 16.1875 11.3457 17.5332 13 17.5332C14.6543 17.5332 16 16.1875 16 14.5332C16 12.8789 14.6543 11.5332 13 11.5332Z" fill=""></path>
                                    </svg>
                                    {{ __('Share') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="shareDropdown">
                                    @foreach($socials as $social)
                                        @php
                                            $name = Arr::get($social, 'name');
                                            $backgroundColor = Arr::get($social, 'background_color');
                                            $color = Arr::get($social, 'color');
                                        @endphp

                                        <li>
                                            <a
                                                aria-label="{{ __('Share on :social', ['social' => $name]) }}"
                                                @style(["background-color: {$backgroundColor}" => $backgroundColor, "color: {$color}" => $color])
                                                href="{{ Arr::get($social, 'url') }}"
                                                target="_blank"
                                                class="dropdown-item"
                                            >
                                                {!! Arr::get($social, 'icon') !!}
                                                <span>{{ $name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-30">
                <div class="col-lg-8">
                    <div class="section-box box-banner-home2 background-body">
                        <div class="container">
                            <div class="container-banner-activities car-detail-galleries">
                                <div class="box-banner-activities">
                                    <div class="banner-activities-detail">
                                        @foreach($images as $image)
                                            <div class="banner-slide-activity">
                                                <div class="wrapper-image">
                                                    {{ RvMedia::image($image, $car->name) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="box-button-abs">
                                        @if ($images)
                                            <a class="btn btn-primary rounded-pill lightbox " href="{{ RvMedia::getImageUrl(Arr::first($images)) }}" data-group="car-image-{{ $car->getKey() }}">
                                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20 8V2.75C20 2.3375 19.6625 2 19.25 2H14C13.5875 2 13.25 2.3375 13.25 2.75V8C13.25 8.4125 13.5875 8.75 14 8.75H19.25C19.6625 8.75 20 8.4125 20 8ZM19.25 0.5C20.495 0.5 21.5 1.505 21.5 2.75V8C21.5 9.245 20.495 10.25 19.25 10.25H14C12.755 10.25 11.75 9.245 11.75 8V2.75C11.75 1.505 12.755 0.5 14 0.5H19.25Z" fill=""></path>
                                                    <path d="M20 19.25V14C20 13.5875 19.6625 13.25 19.25 13.25H14C13.5875 13.25 13.25 13.5875 13.25 14V19.25C13.25 19.6625 13.5875 20 14 20H19.25C19.6625 20 20 19.6625 20 19.25ZM19.25 11.75C20.495 11.75 21.5 12.755 21.5 14V19.25C21.5 20.495 20.495 21.5 19.25 21.5H14C12.755 21.5 11.75 20.495 11.75 19.25V14C11.75 12.755 12.755 11.75 14 11.75H19.25Z" fill=""></path>
                                                    <path d="M8 8.75C8.4125 8.75 8.75 8.4125 8.75 8V2.75C8.75 2.3375 8.4125 2 8 2H2.75C2.3375 2 2 2.3375 2 2.75V8C2 8.4125 2.3375 8.75 2.75 8.75H8ZM8 0.5C9.245 0.5 10.25 1.505 10.25 2.75V8C10.25 9.245 9.245 10.25 8 10.25H2.75C1.505 10.25 0.5 9.245 0.5 8V2.75C0.5 1.505 1.505 0.5 2.75 0.5H8Z" fill=""></path>
                                                    <path d="M8 20C8.4125 20 8.75 19.6625 8.75 19.25V14C8.75 13.5875 8.4125 13.25 8 13.25H2.75C2.3375 13.25 2 13.5875 2 14V19.25C2 19.6625 2.3375 20 2.75 20H8ZM8 11.75C9.245 11.75 10.25 12.755 10.25 14V19.25C10.25 20.495 9.245 21.5 8 21.5H2.75C1.505 21.5 0.5 20.495 0.5 19.25V14C0.5 12.755 1.505 11.75 2.75 11.75H8Z" fill=""></path>
                                                </svg>
                                                {{ __('See All Photos') }}
                                            </a>
                                        @endif

                                        @foreach($images as $image)
                                            @continue($loop->first)
                                            <a class="lightbox d-none" href="{{ RvMedia::getImageUrl($image) }}" data-group="car-image-{{ $car->getKey() }}"></a>
                                        @endforeach


                                        @if($youtubeId)
                                            <a class="btn btn-white-md popup-youtube" href="https://www.youtube.com/watch?v={{ $youtubeId }}">
                                                <img src="{{ Theme::asset()->url('images/icons/play-video.svg') }}" alt="Carento"> {{ __('Video Clips') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="slider-thumnail-activities">
                                    <div class="slider-nav-thumbnails-activities-detail">
                                        @foreach($images as $image)
                                            <div class="banner-slide">
                                                {{ RvMedia::image($image, $car->name, 'medium-rectangle') }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Theme::partial('cars.car-detail.includes.attributes', compact('car')) !!}

                    {!! Theme::partial('cars.car-detail.includes.additional-info', compact('car')) !!}

                    <div class="box-collapse-expand">
                        {!! Theme::partial('cars.car-detail.includes.content', compact('car')) !!}

                        {!! Theme::partial('cars.car-detail.includes.owner-info', compact('car')) !!}

                        {!! Theme::partial('cars.car-detail.includes.faqs', compact('car')) !!}

                        @if(CarRentalsHelper::isEnabledCarReviews())
                            @php
                                $overviewReviews = CarRentalsHelper::getReviewsGroupedByCarId($car->id, $car->reviews_count);
                            @endphp
                            <div class="group-collapse-expand">
                                <button class="btn btn-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
                                    <strong class="heading-6">{{ __('Rate & Reviews') }}</strong>
                                    <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1L6 6L11 1" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                                <div class="collapse show" id="collapseReviews">
                                    <div class="card card-body">
                                        <div class="head-reviews">
                                            <div class="review-left">
                                                <div class="review-info-inner">
                                                    <strong class="heading-6 neutral-1000">{{ __(':avg_star/5', ['avg_star' => $reviewAvg = round($car->reviews_sum_star / ($car->reviews_count ?: 1), 2)]) }}</strong>
                                                    @if ($reviewCount = $car->reviews_count)
                                                        <p class="text-sm-medium neutral-400">({{ __(':number reviews', ['number' => $reviewCount]) }})</p>
                                                    @endif

                                                    @include(CarRentalsHelper::viewPath('reviews.includes.rating-star'), ['avg' => $reviewAvg])
                                                </div>
                                            </div>

                                            <div class="review-right">
                                                <div class="review-progress">
                                                    @foreach($overviewReviews as $overviewReview)
                                                        <div class="item-review-progress">
                                                            <div class="text-rv-progress">
                                                                <p class="text-sm-bold">{{ __(':number Star', ['number' => Arr::get($overviewReview, 'star')]) }}</p>
                                                            </div>
                                                            <div class="bar-rv-progress">
                                                                <div class="progress">
                                                                    <div class="progress-bar" style="width: {{ Arr::get($overviewReview, 'percent') }}%;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="text-avarage">
                                                                <p>{{ number_format(Arr::get($overviewReview, 'count') ?: 0, 2) }}%</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @include(CarRentalsHelper::viewPath('reviews.includes.items'))

                                        @if ($reviews instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $reviews->total() > 0)
                                            {{ $reviews->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="group-collapse-expand">
                                <button class="btn btn-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddReview" aria-expanded="false" aria-controls="collapseAddReview">
                                    <strong class="heading-6">{{ __('Add a review') }}</strong>
                                    <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1L6 6L11 1" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                                <div class="collapse show" id="collapseAddReview">
                                    <div class="card card-body">
                                        @include(CarRentalsHelper::viewPath('reviews.form'))
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    @if($car->is_for_sale)
                        {!! Theme::partial('cars.car-detail.includes.sale-info', compact('car')) !!}
                    @else
                        {!! Theme::partial('cars.car-detail.includes.booking-form', compact('car')) !!}
                    @endif

                    @include(Theme::getThemeNamespace('views.car-rentals.message-form'), compact('car'))
                </div>
            </div>
        </div>
    </div>
</div>
