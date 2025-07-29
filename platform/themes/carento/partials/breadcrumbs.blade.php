@php
    $bgImage = Theme::get('breadcrumb_background_image') ?: theme_option('breadcrumb_background_image');
    $bgColor = Theme::get('breadcrumb_background_color') ?: theme_option('breadcrumb_background_color');
    $textColor = Theme::get('breadcrumb_text_color') ?: theme_option('breadcrumb_text_color');
    $height = Theme::get('breadcrumb_height') ?: theme_option('breadcrumb_height');
    $height = (int) $height ? $height . 'px' : 'auto';
    $hasTextColor = $textColor && $textColor !== 'transparent';
    $isSimple = Theme::get('breadcrumb_simple') ?: theme_option('breadcrumb_simple');
    $breadcrumbDescription = Theme::get('breadcrumb_description');
    $breadcrumbEnabled = Theme::get('breadcrumbEnabled', true);
@endphp

@if ($breadcrumbEnabled)
    @if(! $isSimple)
        <div class=" page-header pt-30 background-body">
            <div class="breadcrumb__area breadcrumb__bg custom-container position-relative mx-auto rounded-12"
                @style([
                    '--breadcrumb-bg-color: ' . $bgColor => $bgColor,
                    '--breadcrumb-txt-color: ' . $textColor => $hasTextColor,
                    '--breadcrumb-height: ' . $height => $height,
                ])
            >
                @if ($bgImage)
                    <div class="bg-overlay rounded-12 overflow-hidden">
                        {{ RvMedia::image($bgImage, '', attributes: ['class' => 'w-100 h-100 img-banner']) }}
                    </div>
                @endif

                @if($pageTitle = Theme::get('pageTitle'))
                    <div class="container position-absolute z-1 top-50 start-50 translate-middle">
                        <h2 class="text-white">{!! BaseHelper::clean($pageTitle) !!}</h2>
                        @if ($pageDescription = Theme::get('pageDescription'))
                            <span class="text-white text-xl-medium d-none d-md-block">{!! BaseHelper::clean($pageDescription) !!}</span>
                        @endif
                        @if($breadcrumbDescription)
                            <span class="text-white text-xl-medium">{{ __('Last update: :date', ['date' => $breadcrumbDescription]) }}</span>
                        @endif
                    </div>
                @endif

                <div class="background-body position-absolute z-1 top-100 start-50 translate-middle px-3 py-2 rounded-12 border d-none d-md-flex gap-3">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $crumb)
                        @if (! $loop->last)

                            <a href="{{ $crumb['url'] }}" title="{{ $crumb['label'] }}" class="neutral-700 text-md-medium">{{ $crumb['label'] }}</a>
                            <span>
                        <img src="{{ Theme::asset()->url('images/icons/arrow-right.svg') }}" alt="Icon" />
                    </span>
                        @else
                            <a href="#" class="neutral-1000 text-md-bold">{{ $crumb['label'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <div class="pt-30">
            <div class="text-center  mb-40">
                <div class="background-body px-3 py-2 rounded-12 border d-flex gap-3 d-inline-flex">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $crumb)
                        @if (! $loop->last)

                            <a href="{{ $crumb['url'] }}" title="{{ $crumb['label'] }}" class="neutral-700 text-md-medium">{{ $crumb['label'] }}</a>
                            <span>
                        <img src="{{ Theme::asset()->url('images/icons/arrow-right.svg') }}" alt="Icon" />
                    </span>
                        @else
                            <a href="#" class="neutral-1000 text-md-bold">{{ $crumb['label'] }}</a>
                        @endif
                    @endforeach
                </div>
                @if ($pageTitle = Theme::get('pageTitle'))
                    <h3 class="my-3 neutral-1000">{!! BaseHelper::clean($pageTitle) !!}</h3>
                @endif
            </div>
        </div>
    @endif
@endif
