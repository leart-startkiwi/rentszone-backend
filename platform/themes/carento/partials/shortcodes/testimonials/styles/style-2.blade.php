@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

<section class="shortcode-testimonial section-box py-96 background-body">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-auto mx-auto wow fadeInUp text-center d-flex flex-column align-items-center justify-content-center">
                @if($subtitle)
                    <div class="box-author-testimonials">
                        @foreach($testimonials as $testimonial)
                            {{ RvMedia::image($testimonial->image, $testimonial->name, 'thumb') }}
                        @endforeach

                        {!! BaseHelper::clean($subtitle) !!}
                    </div>
                @endif

                @if($title)
                    <h2 class="heading-3 mt-8 mb-15 shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                @endif
            </div>
        </div>
    </div>
    <div class="block-testimonials wow fadeIn">
        <div class="container-testimonials">
            <div class="container-slider ps-0">
                <div class="box-swiper mt-30">
                    <div class="swiper-container swiper-group-animate swiper-group-journey">
                        <div class="swiper-wrapper">
                            @foreach($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="card-testimonial background-card">
                                        @if($content = $testimonial->content)
                                            <div class="card-info">
                                                <p class="text-md-regular neutral-500">{!! BaseHelper::clean($content) !!}</p>
                                            </div>
                                        @endif

                                        <div class="card-top pt-40 border-0 mb-0">
                                            <div class="card-author">
                                                <div class="card-image">
                                                    {{ RvMedia::image($testimonial->image, $testimonial->name, attributes: ['style' => 'object-fit: cover !important;']) }}
                                                </div>
                                                <div class="card-info">
                                                    <p class="text-lg-bold neutral-1000">{!! BaseHelper::clean($testimonial->name) !!}</p>

                                                    @if($company = $testimonial->company)
                                                        <p class="text-md-regular neutral-1000">{!! BaseHelper::clean($company) !!}</p>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="card-rate">
                                                @php
                                                    $start = (int) $testimonial->getMetaData('rating_star', true) ?: 5;
                                                @endphp

                                                @for($i = 0; $i < $start; $i++)
                                                    <img class="background-brand-2 p-1" src="{{ Theme::asset()->url('images/icons/star-black.svg') }}" alt="icon star" />
                                                @endfor
                                            </div>
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
