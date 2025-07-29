@php
    Theme::asset()->container('footer')->usePath()->add('no-ui-slider', 'js/noUISlider.js');

    $title = $shortcode->title;
    $subTitle = $shortcode->subtitle;
    $enableFilter = CarRentalsHelper::isEnabledCarFilter() ? $shortcode->enable_filter : 'no';
    $defaultLayout = $shortcode->default_layout;
    $layoutCol = $shortcode->layout_col;
@endphp

<section class="section-box pt-50 background-body">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-9 mb-30 wow fadeInUp">
                @if($title)
                    <h4 class="title-svg shortcode-title mb-15">{{ BaseHelper::clean($title) }}</h4>
                @endif
                @if($subTitle)
                    <p class="text-lg-medium text-bold shortcode-subtitle">{{ BaseHelper::clean($subTitle) }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
<section class="box-section block-content-tourlist background-body">
    <div class="container">
        <div class="box-content-main pt-20">
            @include(Theme::getThemeNamespace('views.car-list.partials.car-items',[
                    'cars' => $cars,
                    'defaultLayout' => $defaultLayout,
                    'perPages' => $perPages,
                    'layoutCol' => $layoutCol,
                    'enableFilter' => $enableFilter
                ])
            )

            @if($enableFilter === 'yes')
                @include(Theme::getThemeNamespace('views.car-list.partials.filters', [
                        'defaultLayout' => $defaultLayout,
                        'layoutCol' => $layoutCol,
                        'enableFilter' => $enableFilter
                    ])
                )
            @endif
        </div>
    </div>
</section>
