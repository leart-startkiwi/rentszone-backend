@php
    $backgroundColor = $shortcode->background_color;
@endphp

@if(count($tabs))
    <section class="section-static-1 shortcode-site-statistics shortcode-site-statistics-style-2 background-body">
        <div class="container">
            <div class="row">
                <div class="rounded-12 background-3 pt-30 pb-30"
                @style(["--background-color: $backgroundColor" => $backgroundColor])
                >
                    <div class="wow fadeIn">
                        <div class="d-flex align-items-center justify-content-around flex-wrap">
                            @foreach($tabs as $item)
                                @php
                                    $title = Arr::get($item, 'title');
                                    $data = Arr::get($item, 'data');
                                @endphp
                                @continue(! $data || ! $title)
                                <div class="mb-4 mb-lg-0 d-block px-lg-5 px-3">
                                    <div class="d-flex justify-content-center justify-content-md-start">
                                        <h3 class="count neutral-1000"><span class="odometer" data-count="{{ $data }}"></span></h3>
                                        @if ($unit = Arr::get($item, 'unit'))
                                            <h2 class="heading-3 neutral-1000">{!! BaseHelper::clean($unit) !!}</h2>
                                        @endif

                                    </div>
                                    <div class="text-md-start text-center">
                                        <p class="text-lg-bold neutral-1000">{!! BaseHelper::clean($title) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
