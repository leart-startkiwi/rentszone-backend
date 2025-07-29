@php
    Theme::set('pageTitle', $page->name);
    Theme::set('pageDescription', $page->description);
    Theme::set('isHomepage', BaseHelper::isHomepage($page->getKey()));
    Theme::set('breadcrumb_background_image', $page->getMetaData('breadcrumb_background_image', true));
    Theme::set('breadcrumb_simple', $page->getMetaData('breadcrumb_simple', true));
    Theme::set('breadcrumb_background_color', $page->getMetaData('breadcrumb_background_color', true));
    Theme::set('breadcrumb_text_color', $page->getMetaData('breadcrumb_text_color', true));
    Theme::set('breadcrumb_description', $page->getMetaData('breadcrumb_display_last_update', true) ? $page->updated_at : $page->description);
@endphp

{!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, Html::tag('div', BaseHelper::clean($page->content), ['class' => 'ck-content'])->toHtml(), $page) !!}
