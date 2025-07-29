@php
    $backgroundColor = theme_option('footer_background_color', '#FFFFFF');
    $textColor = theme_option('footer_text_color', theme_option('text_color', '#3E4073'));
    $headingColor = theme_option('footer_heading_color', theme_option('primary_color', '#14176C'));
    $backgroundImage = theme_option('footer_background_image');
    $borderColor = theme_option('footer_border_color', '#CFDDE2');
    $backgroundImage = $backgroundImage ? RvMedia::getImageUrl($backgroundImage) : null;
@endphp

{!! apply_filters('ads_render', null, 'footer_before', ['class' => 'mb-2']) !!}

<footer class="footer" @style([
    "--footer-background-color: $backgroundColor",
    "--footer-heading-color: $headingColor",
    "--footer-text-color: $textColor",
    "--footer-border-color: $borderColor",
    "--footer-background-image: url($backgroundImage)" => $backgroundImage,
])>
    <div class="container">
        <div class="footer-top">
            {!! dynamic_sidebar('top_footer_sidebar') !!}
        </div>
        <div class="row">
            {!! dynamic_sidebar('footer_sidebar') !!}
        </div>
        <div class="footer-bottom mt-50">
            <div class="row row align-items-center justify-content-center">
                {!! dynamic_sidebar('bottom_footer_sidebar') !!}
            </div>
        </div>
    </div>
</footer>

{!! apply_filters('ads_render', null, 'footer_after', ['class' => 'mt-2']) !!}
