<?php

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;

app()->booted(function (): void {
    if (! is_plugin_active('contact')) {
        return;
    }

    add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
        return Theme::getThemeNamespace('partials.shortcodes.contact-form.index');
    }, 120);

    Shortcode::modifyAdminConfig('contact-form', function (ShortcodeForm $form) {
        $attributes = is_array($form->getModel()) ? $form->getModel() : [];

        $form
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                ->label(__('Button label'))
            )
            ->add(
                'show_map',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(__('Show map?'))
            )
            ->add(
                'map_title',
                TextField::class,
                TextFieldOption::make()
                ->collapsible('show_map', true, $attributes['show_map'] ?? false)
                ->label(__('Map title'))
            )
            ->add(
                'map_address',
                TextareaField::class,
                TextareaFieldOption::make()
                ->collapsible('show_map', true, $attributes['show_map'] ?? false)
                ->label(__('Map Address'))
            );

        return $form;
    });
});
