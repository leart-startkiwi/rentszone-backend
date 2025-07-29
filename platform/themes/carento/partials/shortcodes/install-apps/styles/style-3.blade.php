@php
    $styles = [
        "background-color: $bgColor" => $bgColor,
    ];
@endphp

<section
    class="box-app-2 position-relative pb-80"
    @style($styles)
>
    <div class="container bg-4 rounded-12 overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-6 p-5">
                @if(empty($buttonLabel) === false)
                    <span class="btn btn-primary background-brand-2">{!! BaseHelper::clean($buttonLabel) !!}</span>
                @endif
                    @if(!empty($title))
                        <h4 class="mt-4 mb-3 shortcode-title">{!! BaseHelper::clean($title) !!}</h4>
                    @endif
                    @if(!empty($appsDescription))
                        <p class="text-md-medium pb-3 neutral-500">{!! BaseHelper::clean($appsDescription) !!}</p>
                    @endif
                <div class="download-apps mt-0">
                    @if(!empty($androidAppImage))
                        <a class=" wow fadeInUp" href="{{ $androidAppUrl }}">
                            {{ RvMedia::image($androidAppImage) }}
                        </a>
                    @endif
                    @if(!empty($iosAppImage))
                        <a class=" wow fadeInUp" data-wow-delay="0.2s" href="{{ $iosAppUrl }}">
                            {{ RvMedia::image($iosAppImage) }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 px-0 align-self-stretch">
                @if(!empty($decorImage))
                    <div class="box-app-img d-lg-flex align-items-end d-none">
                        {{ RvMedia::image($decorImage) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
