@php
    $styles = [
        "--background-box-app: url($bgImage)" => $bgImage,
        "background-color: $bgColor" => $bgColor,
    ];
@endphp
<section
    class="widget-install-app box-app position-relative"
    @style($styles)
>
    <div class="container position-relative z-1">
        <div class="row align-items-center py-5">
            <div class="col-lg-5">
                @if(!empty($title))
                    <h4 class=" wow fadeInDown shortcode-title">{!! BaseHelper::clean($title) !!}</h4>
                @endif
                @if(!empty($appsDescription))
                    <p class="text-md-medium pb-3 wow fadeInUp">{!! BaseHelper::clean($appsDescription) !!}</p>
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
            <div class="col-lg-7">
                @if(!empty($decorImage))
                    <div class="box-app-img wow fadeIn">
                        {{ RvMedia::image($decorImage) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
