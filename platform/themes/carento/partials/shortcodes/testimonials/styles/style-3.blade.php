@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

<section class="section-box py-96 background-body border-bottom border-top">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-6">
                <div class="box-author-testimonials background-2 wow fadeInDown">
                    @foreach($testimonials as $testimonial)
                        {{ RvMedia::image($testimonial->image, $testimonial->name, 'thumb') }}
                    @endforeach

                    @if($subtitle)
                        {!! BaseHelper::clean($subtitle) !!}
                    @endif
                </div>

                @if($title)
                    <h4 class="mt-8 mb-15 shortcode-title wow fadeInUp">
                        {!! BaseHelper::clean($title) !!}
                    </h4>
                @endif

                <div class="d-flex flex-wrap flex-lg-nowrap gap-3 mt-4">
                    @foreach($testimonials as $testimonial)
                        {{ RvMedia::image($testimonial->image, $testimonial->name, 'thumb', attributes: ['class' => 'icon_70']) }}
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box-swiper mt-40">
                    <div class="swiper-container swiper-group-1 swiper-group-journey pb-0">
                        <div class="swiper-wrapper">
                            @foreach($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="px-lg-5">
                                        <div class="card-top">
                                            <div class="card-author">
                                                <div class="me-3">
                                                    {{ RvMedia::image($testimonial->image, $testimonial->name, attributes: ['style' => 'object-fit: cover !important;', 'class' => 'icon_70']) }}
                                                </div>
                                                <div class="card-info">
                                                    <p class="text-lg-bold neutral-1000">{!! BaseHelper::clean($testimonial->name) !!}</p>
                                                    <div class="card-rate">
                                                        @php
                                                            $start = (int) $testimonial->getMetaData('rating_star', true) ?: 5;
                                                        @endphp

                                                        @for($i = 0; $i < $start; $i++)
                                                            <img class="p-1" src="{{ Theme::asset()->url('images/icons/star-yellow.svg') }}" alt="icon star" />
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($content = $testimonial->content)
                                            <div class="card-info my-3">
                                                <p class="neutral-1000 text-md">{!! BaseHelper::clean($content) !!}</p>
                                            </div>
                                        @endif

                                        <div class="card-bottom">
                                            <p class="text-sm-bold neutral-1000">- 25 January 2024 -</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
