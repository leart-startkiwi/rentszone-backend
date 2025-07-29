@if($teams->isNotEmpty())
    <section class="section-team-1 py-96 background-body border-top border-bottom shortcode-team">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                    <div class="text-center mb-5">
                        @if ($subtitle = $shortcode->subtitle)
                            <span class="text-xl-medium shortcode-subtitle">{!! BaseHelper::clean($subtitle) !!}</span>
                        @endif

                        @if ($title = $shortcode->title)
                            <h2 class="heading-3 section-title shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mt-50">
                @foreach($teams as $team)
                    <div class="col-lg-3 col-md-6 col-12 mb-40">
                        <div class="card-news background-card hover-up shadow-2 mb-4 mb-lg-0">
                            <div class="card-image">
                                <a href="{{ $team->url }}">
                                    {{ RvMedia::image($team->photo, $team->name, 'medium-square') }}
                                </a>
                            </div>
                            <div class="card-info p-4">
                                <div class="card-title">
                                    <a class="text-xl-bold neutral-1000" href="{{ $team->url }}">
                                        <h6>{{ $team->name }}</h6>
                                    </a>

                                    @if($teamTitle = $team->title)
                                        <span class="text-sm-medium neutral-500">{!! BaseHelper::clean($teamTitle) !!}</span>
                                    @endif
                                </div>
                                <div class="card-program">
                                    <div class="endtime">
                                        <div class="card-author d-flex align-items-center gap-2">
                                            @foreach($team->socials as $key => $social)
                                                <a href="{{ $social }}" class="rounded-circle background-100 icon-shape icon icon-sm hover-up">
                                                    <x-core::icon name="ti ti-brand-{{ $key }}" class="m-0" />
                                                </a>
                                            @endforeach
                                        </div>
                                        <a href="{{ $team->url }}" class="rounded-circle background-100 icon-shape icon icon-sm hover-up border icon-shape-arrow">
                                            <img class="m-0" src="{{ Theme::asset()->url('images/icons/arrow-up-right.svg') }}" alt="icon" />

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
