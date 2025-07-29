@php
    $bgImage = $shortcode->background_image ? RvMedia::getImageUrl($shortcode->background_image) : null;
@endphp

<div class="page-header-2 pt-30 background-body">
    <div class="custom-container position-relative mx-auto">
        <div class="bg-overlay rounded-12 overflow-hidden">
            <img class="w-100 h-100 img-fluid img-banner" src="{{ $bgImage }}" alt="Carento" />
        </div>
        <div class="container position-absolute z-1 top-50 start-50 pb-70 translate-middle text-center">
            @if ($tag = $shortcode->tag)
                <span class="text-sm-bold bg-2 px-4 py-3 rounded-12">{{ $tag }}</span>
            @endif
            @if ($title = $shortcode->title)
                <h2 class="text-white mt-4 shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
            @endif
            @if ($subTitle = $shortcode->subtitle)
                <span class="text-white text-lg-medium">{{ $subTitle }}</span>
            @endif
        </div>
    </div>
</div>
