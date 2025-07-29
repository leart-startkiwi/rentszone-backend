{!! SeoHelper::render() !!}

@include(CarRentalsHelper::viewPath('vendor-dashboard.layouts.header-meta'))

<link
    href="{{ asset('vendor/core/plugins/car-rentals/fonts/linearicons/linearicons.css') }}?v={{ CarRentalsHelper::getAssetVersion() }}"
    rel="stylesheet"
>
<link
    href="{{ asset('vendor/core/plugins/car-rentals/css/dashboard.css') }}?v={{ CarRentalsHelper::getAssetVersion() }}"
    rel="stylesheet"
>

@if (session('locale_direction', 'ltr') == 'rtl')
    <link href="{{ asset('vendor/core/core/base/css/core.rtl.css') }}" rel="stylesheet">

    <link
        href="{{ asset('vendor/core/plugins/car-rentals/css/dashboard-rtl.css') }}?v={{ CarRentalsHelper::getAssetVersion() }}"
        rel="stylesheet"
    >
@endif

@if (File::exists($styleIntegration = Theme::getStyleIntegrationPath()))
    {!! Html::style(Theme::asset()->url('css/style.integration.css?v=' . filectime($styleIntegration))) !!}
@endif
