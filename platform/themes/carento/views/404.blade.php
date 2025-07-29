@php
    SeoHelper::setTitle(__('404 - Not found'));
    Theme::fireEventGlobalAssets();
@endphp

@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    <div class="container pt-140 pb-170 page-404-content">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <h1>{{ __('404') }}</h1>
                    <h5>{{ __('Page Not Found') }}</h5>
                    <p class="text-md-medium neutral-500 text-center">{!! __('Page not available. Please search again or <br> navigate using the menu.') !!}</p>
                    <a href="{{ BaseHelper::getHomepageUrl() }}" class="btn btn-primary mt-30">
                        <img src="{{ Theme::asset()->url('images/icons/arrow-left.svg') }}" alt="icon">
                        {{ __('Back to Home') }}
                    </a>
                    <img src="{{ Theme::asset()->url('images/404.png') }}" alt="{{ theme_option('site_title') }}">
                </div>
            </div>
        </div>
    </div>
@endsection

