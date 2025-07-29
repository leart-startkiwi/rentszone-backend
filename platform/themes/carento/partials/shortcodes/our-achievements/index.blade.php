@php
    $backgroundColor = $shortcode->background_color;
    $style = [
        "background-color: $backgroundColor" => $backgroundColor
    ];
@endphp
<section class="shortcode-our-achievements section-static-1 background-body">
    <div class="container">
        <div class="row">
            <div class="rounded-12 pt-30 pb-30" @style($style)>
                <div class="wow fadeIn">
                    <div class="d-flex align-items-center justify-content-around flex-wrap">
                        @foreach($achievements as $achievement)
                            @php
                                $title = Arr::get($achievement, 'title');
                                $subtitle = Arr::get($achievement, 'subtitle');
                                $value = Arr::get($achievement, 'value') ?: 0;
                                $unit = Arr::get($achievement, 'unit');
                            @endphp
                            @continue(empty($title) && empty($subtitle))

                            <div class="mb-4 mb-lg-0 d-block px-lg-5 px-3">
                                <div class="d-flex justify-content-center justify-content-md-start">
                                    <h3 class="count neutral-1000"><span class="odometer" data-count="{{ $value }}"></span></h3>
                                    @if(empty($unit) === false)
                                        <h2 class="heading-3 neutral-1000">{!! BaseHelper::clean($unit) !!}</h2>
                                    @endif
                                </div>
                                <div class="text-md-start text-center">
                                    @if(empty($title) === false)
                                        <p class="text-lg-bold neutral-1000">{!! BaseHelper::clean($title) !!}</p>
                                    @endif
                                    @if(empty($subtitle) === false)
                                        <p class="text-lg-bold neutral-1000">{!! BaseHelper::clean($subtitle) !!}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
