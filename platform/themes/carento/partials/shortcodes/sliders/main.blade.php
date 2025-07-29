@if (
    is_plugin_active('simple-slider') && count($sliders) > 0 &&
    $sliders->loadMissing('metadata') && $slider->loadMissing('metadata')
)
    @php
        $style = $shortcode->style;
        $style = $style ? (in_array($style, ['style-1', 'style-2', 'style-3']) ? $style : 'style-1') : 'style-1';
        $contentOnTop = $slider->getMetaData('content_on_top', true);
        $footerOnTop = $slider->getMetaData('footer_on_top', true);
    @endphp

    {!! Theme::partial("shortcodes.sliders.styles.$style", compact('shortcode', 'contentOnTop', 'footerOnTop', 'sliders')) !!}
@endif
