<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\Facades\Theme;
use Botble\Theme\ThemeOption\Fields\ColorField;
use Botble\Theme\ThemeOption\Fields\MediaImageField;
use Botble\Theme\ThemeOption\Fields\NumberField;
use Botble\Theme\ThemeOption\Fields\SelectField;
use Botble\Theme\ThemeOption\Fields\TextareaField;
use Botble\Theme\ThemeOption\Fields\TextField;
use Botble\Theme\ThemeOption\Fields\ToggleField;
use Botble\Theme\ThemeOption\Fields\UiSelectorField;

app('events')->listen(RenderingThemeOptionSettings::class, function (): void {
    theme_option()
        ->setField([
            'id' => 'logo_dark',
            'section_id' => 'opt-text-subsection-logo',
            'type' => 'mediaImage',
            'label' => __('Logo dark'),
            'attributes' => [
                'name' => 'logo_dark',
            ],
        ])
        ->setSection([
            'title' => __('Header'),
            'id' => 'opt-text-subsection-header',
            'subsection' => true,
            'icon' => 'ti ti-layout-navbar',
            'fields' => [
                ToggleField::make()
                    ->label(__('Display header top?'))
                    ->id('display_header_top')
                    ->defaultValue(true)
                    ->name('display_header_top'),
                ColorField::make()
                    ->label(__('Header top text color'))
                    ->id('header_top_text_color')
                    ->name('header_top_text_color'),
                ColorField::make()
                    ->label(__('Header top background color'))
                    ->id('header_top_background_color')
                    ->name('header_top_background_color'),
                ToggleField::make()
                    ->label(__('Header transparent?'))
                    ->name('is_header_transparent')
                    ->helperText('When this feature is enabled, the header will have a transparent background and will float to the top of the page.'),
            ],
        ])
        ->setSection([
            'title' => __('Footer'),
            'id' => 'opt-text-subsection-footer',
            'subsection' => true,
            'icon' => 'ti ti-layout-bottombar',
            'fields' => [
                ColorField::make()
                    ->name('footer_background_color')
                    ->label(__('Background color')),
                ColorField::make()
                    ->name('footer_border_color')
                    ->label(__('Border color')),
                ColorField::make()
                    ->name('footer_heading_color')
                    ->label(__('Text heading color')),
                ColorField::make()
                    ->name('footer_text_color')
                    ->label(__('Text color')),
                MediaImageField::make()
                    ->name('footer_background_image')
                    ->label(__('Background image')),
            ],
        ])
        ->setSection([
            'title' => __('Styles'),
            'id' => 'opt-text-subsection-styles',
            'subsection' => true,
            'icon' => 'ti ti-brush',
            'fields' => [
                SelectField::make()
                    ->label(__('Default theme color mode'))
                    ->name('default_theme_color_mode')
                    ->defaultValue('light')
                    ->options([
                        'light' => __('Light'),
                        'dark' => __('Dark'),
                    ]),
                SelectField::make()
                    ->label(__('Hide theme mode switcher'))
                    ->name('hide_theme_mode_switcher')
                    ->defaultValue('no')
                    ->options([
                        'no' => __('No'),
                        'yes' => __('Yes'),
                    ]),
                ColorField::make()
                    ->name('primary_color')
                    ->value('#ff2b4a')
                    ->label(__('Primary color')),
                ColorField::make()
                    ->name('primary_color_hover')
                    ->value('#5edd5b')
                    ->label(__('Primary color hover')),
                ColorField::make()
                    ->name('secondary_color')
                    ->value('#191D88')
                    ->label(__('Secondary color')),
                ColorField::make()
                    ->name('primary_badge_background_color')
                    ->value('#d8f4db')
                    ->label(__('Primary badge background color')),
                ColorField::make()
                    ->name('heading_color')
                    ->value('#14176C')
                    ->label(__('Heading color')),
                ColorField::make()
                    ->name('text_color')
                    ->value('#3E4073')
                    ->label(__('Text color')),
            ],
        ])
        ->setField([
            'id' => 'blog_post_list_meta_display',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'multiChecklist',
            'label' => __('Post meta display (blog list)'),
            'attributes' => [
                'name' => 'blog_post_list_meta_display[]',
                'value' => json_decode(
                    theme_option('blog_post_list_meta_display', '["reading_time", "views_count", "published_date", "author"]')
                ) ?: [],
                'choices' => $postMetaOptions = [
                    'published_date' => __('Published date'),
                    'reading_time' => __('Reading time'),
                    'views_count' => __('Views count'),
                    'author' => __('Author'),
                ],
            ],
        ])
        ->setField(
            TextField::make()
                ->id('blog_post_list_page_title')
                ->name('blog_post_list_page_title')
                ->sectionId('opt-text-subsection-blog')
                ->label(__('Blog post list page title'))
        )
        ->setField(
            TextareaField::make()
                ->id('blog_post_list_page_description')
                ->name('blog_post_list_page_description')
                ->sectionId('opt-text-subsection-blog')
                ->label(__('Blog post list page description'))
        )
        ->setField(
            SelectField::make()
            ->name('blog_post_style')
            ->label(__('Blog posts style'))
            ->sectionId('opt-text-subsection-blog')
            ->id('blog_post_style')
            ->defaultValue('grid')
            ->options([
                'list' => __('List'),
                'grid' => __('Grid'),
            ])
        )
        ->setField(
            SelectField::make()
                ->name('blog_post_gird_items_per_row')
                ->label(__('Blog post grid items per row'))
                ->sectionId('opt-text-subsection-blog')
                ->id('blog_post_gird_items_per_row')
                ->helperText(__('Only apply for grid style'))
                ->defaultValue(2)
                ->options([
                    1 => __(':number Post', ['number' => 1]),
                    2 => __(':number Posts', ['number' => 2]),
                    3 => __(':number Posts', ['number' => 3]),
                ])
        )
        ->setField(
            ColorField::make()
                ->sectionId('opt-text-subsection-breadcrumb')
                ->id('breadcrumb_background_color')
                ->name('breadcrumb_background_color')
                ->value('')
                ->label(__('Breadcrumb background color'))
        )
        ->setField(
            ColorField::make()
                ->sectionId('opt-text-subsection-breadcrumb')
                ->id('breadcrumb_text_color')
                ->name('breadcrumb_text_color')
                ->value('transparent')
                ->label(__('Breadcrumb text color'))
        )
        ->setField(
            MediaImageField::make()
                ->sectionId('opt-text-subsection-breadcrumb')
                ->id('breadcrumb_background_image')
                ->name('breadcrumb_background_image')
                ->label(__('Breadcrumb background image'))
        )
        ->setField(
            NumberField::make()
                ->sectionId('opt-text-subsection-breadcrumb')
                ->id('breadcrumb_height')
                ->name('breadcrumb_height')
                ->label(__('Breadcrumb height (px)'))
                ->helperText(__('Leave empty to use default height.'))
        )
        ->setField(
            ToggleField::make()
                ->sectionId('opt-text-subsection-breadcrumb')
                ->id('breadcrumb_simple')
                ->name('breadcrumb_simple')
                ->label(__('Simple breadcrumb'))
                ->defaultValue(false)
                ->helperText(__('When this feature is enabled, the breadcrumb will be displayed in a simple style.'))
        )
        ->setField([
            'id' => 'blog_post_detail_meta_display_default',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'hidden',
            'attributes' => [
                'name' => 'blog_post_detail_meta_display',
                'value' => '[]',
            ],
        ])
        ->setField([
            'id' => 'blog_post_detail_meta_display',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'multiChecklist',
            'label' => __('Post meta display (blog detail)'),
            'attributes' => [
                'name' => 'blog_post_detail_meta_display[]',
                'value' => json_decode(
                    theme_option(
                        'blog_post_detail_meta_display',
                        '["category", "author", "published_date", "reading_time", "views_count"]'
                    ),
                    true
                ) ?: [],
                'choices' => $postMetaOptions,
                ],
        ])
        ->setField(
            UiSelectorField::make()
                ->id('car_detail_style')
                ->sectionId('opt-text-subsection-car-rentals')
                ->label(__('Car detail style'))
                ->name('car_detail_style')
                ->options(collect(range(1, 4))->mapWithKeys(function ($i) {
                    return ["style-$i" => [
                        'label' => __('Car Detail :number', ['number' => $i]),
                        'image' => Theme::asset()->url(sprintf('images/theme-options/car-details/style-%s.png', $i)),
                    ]];
                })->all())
                ->aspectRatio(UiSelectorField::RATIO_4_3)
                ->numberItemsPerRow(2)
                ->defaultValue('style-1'),
        );
});
