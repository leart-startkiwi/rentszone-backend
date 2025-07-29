<style>
    :root {
        --primary-color: {{ $primaryColor = theme_option('primary_color', '#70f46d') }} !important;
        --primary-color-hover: {{ $primaryColor = theme_option('primary_color_hover', '#5edd5b') }} !important;
        --primary-badge-background-color: {{ $primaryColor = theme_option('primary_badge_background_color', '#d8f4db') }} !important;
        --primary-color-rgb: {{ implode(',', BaseHelper::hexToRgb($primaryColor)) }}; !important;
        --secondary-color: {{ theme_option('secondary_color', 'rgba(45, 74, 44, 0.6)') }} !important;
        --heading-color: {{ theme_option('heading_color', '#000000') }} !important;
        --text-color: {{ theme_option('text_color', '#000000') }} !important;
        --primary-font: {{ theme_option('tp_primary_font', 'Urbanist') }} !important;
        --heading-font: {{ theme_option('tp_heading_font', 'Urbanist') }} !important;
    }
</style>
