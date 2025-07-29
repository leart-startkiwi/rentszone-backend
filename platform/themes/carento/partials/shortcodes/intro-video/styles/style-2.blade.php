@use(Botble\Theme\Supports\Youtube;)

@php
    $youtubeUrl = $shortcode->youtube_video_url;

    $youtubeId = $youtubeUrl ? Youtube::getYoutubeVideoID($youtubeUrl) : null;
@endphp

<section class="shortcode-intro-video shortcode-intro-video-style-2 section-cta-7 background-body py-96">
    <div class="box-cta-6">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="card-image d-inline-block position-relative mb-100">
                        {{ RvMedia::image($shortcode->image, $shortcode->title, attributes: ['class' => 'rounded-12']) }}

                        @if($youtubeId)
                            <a class="btn btn-play popup-youtube position-absolute top-50 start-50 translate-middle" href="https://www.youtube.com/watch?v={{ $youtubeId }}"></a>
                        @endif

                        @if($image1 = $shortcode->image_1)
                            {{ RvMedia::image($image1, 'image', attributes: ['class' => 'position-absolute top-100 start-100 translate-middle rounded-12 d-none d-md-block']) }}
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    @if($subtitle = $shortcode->subtitle)
                        <span class="btn btn-signin bg-2 text-dark mb-4">{!! BaseHelper::clean($subtitle) !!}</span>
                    @endif

                    @if ($title = $shortcode->title)
                        <h4 class="mb-4 shortcode-title">{!! BaseHelper::clean($title) !!}</h4>
                    @endif

                    @if ($description = $shortcode->description)
                        <p class="text-lg-medium neutral-500 mb-4">
                            {!! BaseHelper::clean($description) !!}
                        </p>
                    @endif

                    @if(count($tabs))
                        <div class="row">
                            <div class="col">
                                <ul class="list-ticks-green list-ticks-green-2">
                                    @foreach($tabs as $tab)
                                        @continue(! $content = Arr::get($tab, 'content'))
                                        <li class="neutral-1000 pe-0">{!! BaseHelper::clean($content) !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(($buttonLabel = $shortcode->button_label) && ($buttonUrl = $shortcode->button_url))
                        <a class="btn btn-primary mt-2" href="{{ $buttonUrl }}">
                            {!! BaseHelper::clean($buttonLabel) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="bg-overlay position-absolute bottom-0 end-0 h-75 background-brand-2 opacity-25 z-0 rounded-start-pill"></div>
    </div>
</section>
