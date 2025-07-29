@php
    $title = $shortcode->title;
    $description = $shortcode->description;
    $middleImage = $shortcode->middle_image;
    $cardsFirst = $cards[0] ?? null;
    $cardsLast = $cards[1] ?? null;

    $backgroundImage = $shortcode->background_image ? RvMedia::getImageUrl($shortcode->background_image) : null;
    $variablesStyle = [
        "--background-image: url($backgroundImage)" => $backgroundImage,
    ];
@endphp

<section class="shortcode-about-us-information section-cta-4 position-relative overflow-hidden" @style($variablesStyle)>
    <div class="bg-shape"></div>
    <div class="container position-relative z-1">
        <div class="text-center">
            @if(empty($title) === false)
                <span class="text-sm-bold bg-white p-3 rounded-12 wow fadeInDown">{!! BaseHelper::clean($title) !!}</span>
            @endif
            @if(empty($description) === false)
                <h4 class="mt-4 wow fadeInUp">{!! BaseHelper::clean($description) !!}</h4>
            @endif
        </div>
        <div class="row mt-60">
            @if($cardsFirst)
                @php
                    $cardIcon = $cardsFirst['icon'] ?? null;
                    $cardTitle = $cardsFirst['title'] ?? '';
                    $cardSubtitle = $cardsFirst['subtitle'] ?? null;
                    $cardButtonUrl = $cardsFirst['button_url'] ?? null;
                    $cardButtonName = $cardsFirst['button_name'] ?? null;
                @endphp
                <div class="col-lg-4">
                    <div class="bg-white rounded-12 p-5 d-flex flex-column gap-4">
                    <span class="icon-shape icon_70 background-2 rounded-circle wow fadeIn">
                            {{ RvMedia::image($cardIcon, $cardTitle) }}
                    </span>
                        @if(empty($cardTitle) === false)
                            <h6 class=" wow fadeInUp">{!! BaseHelper::clean($cardTitle) !!}</h6>
                        @endif
                        @if(empty($cardSubtitle) === false)
                            <p class="text-md-regular wow fadeInUp">{!! BaseHelper::clean($cardSubtitle) !!}</p>
                        @endif
                        @if(empty($cardButtonName) === false)
                            <a class="btn btn-primary wow fadeInUp" href="{{ $cardButtonUrl }}">
                                {!! BaseHelper::clean($cardButtonName) !!}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19L19 12L12 5M19 12L5 12" stroke="#101010" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            @if(empty($middleImage) === false)
                <div class="col-lg-4 text-center wow fadeInUp">
                    {{ RvMedia::image($middleImage, $title) }}
                </div>
            @endif
            @if($cardsLast)
                @php
                    $cardIcon = $cardsLast['icon'] ?? null;
                    $cardTitle = $cardsLast['title'] ?? '';
                    $cardSubtitle = $cardsLast['subtitle'] ?? null;
                    $cardButtonUrl = $cardsLast['button_url'] ?? null;
                    $cardButtonName = $cardsLast['button_name'] ?? null;
                @endphp
                <div class="col-lg-4">
                    <div class="bg-white rounded-12 p-5 d-flex flex-column gap-4">
                <span class="icon-shape icon_70 background-2 rounded-circle wow fadeIn">
                        {{ RvMedia::image($cardIcon, $cardTitle) }}
                </span>
                        @if(empty($cardTitle) === false)
                            <h6 class=" wow fadeInUp">{!! BaseHelper::clean($cardTitle) !!}</h6>
                        @endif
                        @if(empty($cardSubtitle) === false)
                            <p class="text-md-regular wow fadeInUp">{!! BaseHelper::clean($cardSubtitle) !!}</p>
                        @endif
                        @if(empty($cardButtonName) === false)
                            <a class="btn btn-primary wow fadeInUp" href="{{ $cardButtonUrl }}">
                                {!! BaseHelper::clean($cardButtonName) !!}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19L19 12L12 5M19 12L5 12" stroke="#101010" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
