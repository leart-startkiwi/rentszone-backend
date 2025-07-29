<?php

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Testimonial\Models\Testimonial;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

app()->booted(function (): void {
    if (! is_plugin_active('testimonial')) {
        return;
    }

    Shortcode::register(
        'testimonials',
        __('Block Testimonials'),
        __('Add block testimonials'),
        function (ShortcodeCompiler $shortcode): ?string {
            if (! ($testimonialIds = Shortcode::fields()->getIds('testimonial_ids', $shortcode))) {
                return null;
            }

            $testimonials = Testimonial::query()
                ->with('metadata')
                ->whereIn('id', $testimonialIds)
                ->wherePublished()
                ->get();

            if ($testimonials->isEmpty()) {
                return null;
            }

            return Theme::partial('shortcodes.testimonials.index', compact('shortcode', 'testimonials'));
        }
    );
    Shortcode::setPreviewImage('testimonials', Theme::asset()->url('images/shortcodes/testimonials/style-1.png'));
    Shortcode::setAdminConfig('testimonials', function (array $attributes) {
        $testimonials = Testimonial::query()
            ->wherePublished()
            ->pluck('name', 'id')
            ->all();

        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 3))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/testimonials/$style.png"),
                                ],
                            ])
                    )
                    ->selected(Arr::get($attributes, 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'testimonial_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices($testimonials)
                    ->selected(explode(',', Arr::get($attributes, 'testimonial_ids')))
                    ->searchable()
                    ->multiple()
                    ->label(__('Choose testimonials'))
            );
    });
});
