@php
    $col = round(12 / (count($banners) ?: 1));
    $className = "col-lg-$col"
@endphp
<section class="shortcode-simple-banners section-box background-body py-96">
    <div class="container">
        <div class="row">
            @foreach($banners as $banner)
                @php
                    $title = $banner['title'];
                    $image = $banner['image'];
                    $subtitle = $banner['subtitle'] ?? '';
                    $buttonUrl = $banner['button_url'] ?? '/';
                    $buttonName = $banner['button_name'] ?? '';
                    $buttonColor = $banner['button_color'] ?? '';
                    $backgroundColor = $banner['background_color'] ?? '';
                @endphp
                @continue(empty($image) || empty($title))

                <div @class($className)>
                    <div class="box-banner-1 px-5 pt-40 position-relative rounded-12 overflow-hidden" @style(["background-color: {$backgroundColor}"])>
                        <div class="banner-images wow fadeIn">
                            {{ RvMedia::image($image, $title, attributes: ['class' => 'position-absolute bottom-0 end-0']) }}
                        </div>
                        <div class="banner-info">
                            @if(!empty($title))
                                <div class="banner-title wow fadeInDown">
                                    <h5 class="shortcode-title">{!! BaseHelper::clean($title) !!}</h5>
                                </div>
                            @endif
                            @if(!empty($subtitle))
                                <p class="banner-text text-md-regular py-3 wow fadeInUp">
                                    {!! BaseHelper::clean($subtitle) !!}
                                </p>
                            @endif
                            @if(empty($buttonName) === false)
                                <div class="banner-button pb-70 pt-3">
                                    <a class="btn btn-primary wow fadeInUp" href="{{ $buttonUrl }}" @style(["background-color: {$buttonColor}"])>
                                        {!! BaseHelper::clean($buttonName) !!}
                                        <svg class="svg-icon-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
