@use(Botble\Theme\Supports\Youtube;)
@use(Theme\Carento\Support\ThemeHelper;)

@php
    $subtitle = $shortcode->subtitle;
    $title = $shortcode->title;
    $description = $shortcode->description;
    $videoUrl = $shortcode->video_url;
    $image = $shortcode->image;
    $bgColor = $shortcode->background_color;
    $tabChunk = array_chunk($tabs, 3);
    $youtubeUrl = $shortcode->youtube_video_url;

    $youtubeId = $youtubeUrl ? Youtube::getYoutubeVideoID($youtubeUrl) : null;
    $playIconRaw = ThemeHelper::getPlayVideoIconBase64();
@endphp
<section class="shortcode-intro-video shortcode-intro-video-style-1 box-cta-1 background-100 py-96"
    @style([
    "--background-icon: url('data:image/svg+xml;base64,$playIconRaw')" => $playIconRaw,
])
>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 pe-lg-5 wow fadeInUp">
                <div class="card-video">
                    <div class="card-image">
                        @if($youtubeId)
                            <a class="btn btn-play popup-youtube" href="https://www.youtube.com/watch?v={{ $youtubeId }}"></a>
                        @endif

                        {{ RvMedia::image($image, $title, 'medium-square') }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-lg-0 mt-4">
                @if($subtitle)
                    <span class="btn btn-signin bg-white text-dark mb-4 wow fadeInUp">{!! BaseHelper::clean($subtitle) !!}</span>
                @endif
                @if ($title)
                    <h4 class="mb-4 shortcode-title wow fadeInUp">{!! BaseHelper::clean($title) !!}</h4>
                @endif
                @if($description)
                    <p class="text-lg-medium neutral-500 mb-4 wow fadeInUp">{!! BaseHelper::clean($description) !!}</p>
                @endif
                <div class="row">
                    @foreach($tabChunk as $tabs)
                        <div class="col-md-6">
                            <ul class="list-ticks-green">
                                @foreach($tabs as $tab)
                                    @continue(! $contentItem = Arr::get($tab, 'content'))
                                    <li class="neutral-1000 wow fadeInUp" data-wow-delay="0.3s">
                                        <span class="me-1">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                                        </span>

                                        {!! BaseHelper::clean($contentItem) !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
