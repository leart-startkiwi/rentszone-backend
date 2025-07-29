<section class="shortcode-faqs ection-faqs-2 pt-80 pb-80 border-bottom background-body position-relative">
    <div class="container position-relative z-2">
        <div class="text-center mb-40 ">
            @if($title = $shortcode->title)
                <h2 class="heading-3 my-3 shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
            @endif

            @if ($description = $shortcode->description)
                <p class="text-xl-medium shortcode-subtitle">{!! BaseHelper::clean($description) !!}</p>
            @endif
        </div>
        <div class="row">
            @foreach($faqs as $faq)
                @php
                    $id = 'faq-item-' . $faq->getKey();
                @endphp

                <div class="col-lg-6">
                    <div class="accordion">
                        <div class="mb-2 card border">
                            <div class="px-0 card-header border-0 bg-gradient-1 background-card">
                                <a class="collapsed px-3 py-2 text-900 fw-bold d-flex align-items-center" data-bs-toggle="collapse" href="#{{ $id }}">
                                    <p class="text-lg-bold neutral-1000 pe-4">{!! BaseHelper::clean($faq->question) !!}</p>
                                    <span class="ms-auto arrow me-2">
                                        <svg class="invert" xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8" fill="none">
                                            <path class="stroke-dark" d="M11.5 1L6.25 6.5L1 1" stroke="#111827" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                            <div id="{{ $id }}" @class(['collapse',  'show' => $loop->first]) data-bs-parent=".accordion">
                                <p class="pt-0 pb-4 card-body background-body">{!! BaseHelper::clean($faq->answer) !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12 wow fadeInUp mt-4">
                <div class="d-flex justify-content-center gap-2">
                    @if(($btnSecondaryLabel = $shortcode->button_secondary_label) && ($btnSecondaryUrl = $shortcode->button_secondary_url))
                        <a class="btn btn-gray2" href="{{ $btnSecondaryUrl }}">
                            {!! BaseHelper::clean($btnSecondaryLabel) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif

                    @if (($btnPrimaryLabel = $shortcode->button_primary_label) && ($btnPrimaryUrl = $shortcode->button_primary_url))
                        <a class="btn btn-primary rounded-3" href="{{ $btnPrimaryUrl }}">
                            {!! BaseHelper::clean($btnPrimaryLabel) !!}
                            <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
