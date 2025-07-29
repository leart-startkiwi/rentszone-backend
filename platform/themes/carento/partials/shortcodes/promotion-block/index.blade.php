@php
    $backgroundImage = $shortcode->background_image;
    $backgroundImage = $backgroundImage ? RvMedia::getImageUrl($backgroundImage) : null;
@endphp

<section class="shortcode-promotion-block section-box-banner-3 banner-2 background-body"
    @style(["--background-image: url('{$backgroundImage}')" => $backgroundImage])
>
    <div class="container pt-110 pb-110 position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-auto text-center wow fadeInUp justify-content-center d-flex flex-column align-items-center">
                @if ($title = $shortcode->title)
                    <h2 class="text-white shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                @endif

                @if ($subtitle = $shortcode->subtitle)
                    <h6 class="text-white">
                        {!! BaseHelper::clean($subtitle) !!}
                    </h6>
                @endif

                @if (($buttonLabel = $shortcode->button_label) && ($buttonUrl = $shortcode->button_url))
                    <a class="btn btn-primary rounded-pill btn-lg mt-20" href="{{ $buttonUrl }}">
                        {!! BaseHelper::clean($buttonLabel) !!}
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                            <path d="M12.5 19L19.5 12L12.5 5M19.5 12L5.5 12" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
