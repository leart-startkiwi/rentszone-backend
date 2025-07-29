@php
    $styles = [
        "background-color: $bgColor" => $bgColor,
    ];
@endphp
<section
    class="box-app-2 position-relative pt-50"
    @style($styles)
>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-12">
                @if(empty($buttonLabel) === false)
                    <span class="btn btn-primary background-brand-2">{!! BaseHelper::clean($buttonLabel) !!}</span>
                @endif
                @if(!empty($title))
                    <h4 class="mt-4 mb-3 shortcode-title">{!! BaseHelper::clean($title) !!}</h4>
                @endif
                @if(!empty($appsDescription))
                    <p class="text-md-medium pb-3 neutral-500">{!! BaseHelper::clean($appsDescription) !!}</p>
                @endif
                <div class="download-apps mt-0 mb-40">
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
            <div class="col-lg-7 col-md-12">
                @if(!empty($decorImage))
                    <div class="box-app-img d-lg-flex align-items-end d-none">
                        {{ RvMedia::image($decorImage) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
