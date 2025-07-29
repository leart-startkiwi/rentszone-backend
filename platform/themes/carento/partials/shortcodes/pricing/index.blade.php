<section class="shortcode-pricing section-pricing-1 pt-80 pb-100 background-body border-bottom">
    <div class="container">
        <div class="row pb-40 z-1 justify-content-center">
            <div class="col-lg-auto align-self-end">
                @if ($title = $shortcode->title)
                    <h2 class="heading-3 text-center shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                @endif

                <div class="d-flex justify-content-center align-items-center mt-3">
                    <ul class="list-unstyled d-flex align-items-center gap-3 change-price-plan">
                        <li>
                            <a href="#" class="active btn btn-primary monthly px-3 py-2" data-type="monthly">
                                {{ $shortcode->button_label_monthly ?: __('Monthly Price') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="yearly btn btn-white px-3 py-2" data-type="yearly">
                                {{ $shortcode->button_label_yearly ?: __('Annual Price') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($tabs as $item)
                @php
                    $name = Arr::get($item, 'name');
                    $monthlyPrice = Arr::get($item, 'monthly_price');
                    $yearlyPrice = Arr::get($item, 'yearly_price');
                @endphp

                @continue(! $name || ! $monthlyPrice || ! $yearlyPrice)

                <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                    <div class="h-100 p-4 border rounded-12">
                        <h6 class="text-lg-bold neutral-1000">{!! BaseHelper::clean($name) !!}</h6>
                        <div class="d-flex">
                            <span class="heading-3 neutral-1000">$</span>
                            <h3 class="neutral-1000 mb-0 text-price-monthly">{{ $monthlyPrice }}</h3>
                            <h3 class="neutral-1000 mb-0 text-price-yearly" style="display:none;">{{ $yearlyPrice }}</h3>
                            <span class="neutral-500 text-md-medium align-self-end text-type-standard">{{ __('/Mon') }}</span>
                        </div>

                        @if ($description = Arr::get($item, 'description'))
                            <p class="text-sm-medium neutral-1000">{!! BaseHelper::clean($description) !!}</p>
                        @endif

                        <ul class="list-unstyled mb-0 py-4 border-top mt-4">
                            @foreach($features = Arr::get($item, 'features')  as $feature)
                                @if($feature['is_available'])
                                    <li class="d-flex align-items-center mb-3 text-success">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                                        <p title="{{ $feature['value'] }}" class="text-sm-medium neutral-1000 m-0 ms-2 truncate-1-custom">{!! BaseHelper::clean($feature['value']) !!}</p>
                                    </li>
                                @else
                                    <li class="d-flex align-items-center mb-3 text-danger">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-x flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" /></svg>
                                        <p title="{{ $feature['value'] }}" class="text-sm-medium neutral-1000 m-0 ms-2 truncate-1-custom">{!! BaseHelper::clean($feature['value']) !!}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>


                        @if (($buttonLabel = Arr::get($item, 'button_label')) && ($buttonUrl = Arr::get($item, 'button_url')))
                            <a href="{{ $buttonUrl }}" class="btn btn-primary2 w-100 d-flex justify-content-between">
                                {!! BaseHelper::clean($buttonLabel) !!}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path class="fill-dark" d="M17.4177 5.41797L16.3487 6.48705L21.1059 11.2443H0V12.7562H21.1059L16.3487 17.5134L17.4177 18.5825L24 12.0002L17.4177 5.41797Z" fill="#111827" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="rotate-center ellipse-rotate-success position-absolute z-0"></div>
    <div class="rotate-center-rev ellipse-rotate-primary position-absolute z-0"></div>
</section>
