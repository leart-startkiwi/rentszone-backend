@php
    $buttonName = $shortcode->button_label;
    $title = $shortcode->title;
    $buttonUrl = $shortcode->button_url;
    $image1 = $shortcode->image_1;
    $image2 = $shortcode->image_2;
    $image3 = $shortcode->image_3;
    $image4 = $shortcode->image_4;
    $image5 = $shortcode->image_5;
@endphp

<section class="shortcode-featured-block shortcode-featured-block-style-1 shortcode-trusted-expertise background-body">
    <div class="box-cta-3 background-100 py-96 mx-auto rounded-3 position-relative overflow-hidden">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 pe-lg-5">
                    @if ($subtitle = $shortcode->subtitle)
                        <span class="btn btn-signin bg-white text-dark mb-4 wow fadeInDown">{!! BaseHelper::clean($subtitle) !!}</span>
                    @endif

                    @if($title)
                        <h4 class="mb-4 pe-lg-5 shortcode-title wow fadeInUp">{!! BaseHelper::clean($title) !!}</h4>
                    @endif

                    @if ($description = $shortcode->description)
                        <p class="text-lg-medium neutral-500 mb-4 wow fadeInUp">{!! BaseHelper::clean($description) !!}</p>
                    @endif

                    @if (count($tabs) > 0)
                        <div class="row">
                            <div class="col">
                                <ul class="list-ticks-green">
                                    @foreach($tabs as $tab)
                                        @continue(! $content = Arr::get($tab, 'content'))
                                        <li class="neutral-1000">
                                            <span class="me-1">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                                            </span>
                                            {!! BaseHelper::clean($content) !!}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if($buttonName && $buttonUrl)
                        <a class="btn btn-primary mt-2 wow fadeInUp" href="{{ $buttonUrl }}">
                            {!! BaseHelper::clean($buttonName) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="col-lg-6 offset-lg-1 position-relative z-1 mt-lg-0 mt-4">
                    <div class="box-image-payment-2">
                        <div class="row align-items-center">
                            @if($image1)
                                <div class="col-sm-4 mb-30">
                                    {{ RvMedia::image($image1, $title) }}
                                </div>
                            @endif

                            @if($image2 || $image3)
                                <div class="col-sm-4 mb-30">
                                    @if ($image2)
                                        {{ RvMedia::image($image2, $title, attributes: ['class' => 'mb-15']) }}
                                    @endif

                                    @if($image3)
                                        {{ RvMedia::image($image3, $title) }}
                                    @endif
                                </div>
                            @endif
                            <div class="col-sm-4 mb-30">
                                @if($image4)
                                    {{ RvMedia::image($image4, $title, attributes: ['class' => 'mb-15']) }}
                                @endif

                                @if($image5)
                                    {{ RvMedia::image($image5, $title) }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-overlay position-absolute bottom-0 end-0 h-75 background-brand-2 opacity-25 z-0 rounded-start-pill"></div>
    </div>
</section>
