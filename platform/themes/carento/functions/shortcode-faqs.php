<?php

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Faq\FaqCollection;
use Botble\Faq\FaqItem;
use Botble\Faq\FaqSupport;
use Botble\Faq\Models\Faq;
use Botble\Faq\Models\FaqCategory;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

app()->booted(function (): void {
    if (! is_plugin_active('faq')) {
        return;
    }

    Shortcode::register('faqs', __('FAQs'), __('FAQs'), function (ShortcodeCompiler $shortcode) {
        $query = Faq::query();

        $categoryIds = Shortcode::fields()->getIds('faq_category_ids', $shortcode);

        if ($categoryIds) {
            $query->whereIn('category_id', $categoryIds);
        }

        $faqs = $query
            ->wherePublished()
            ->orderByDesc('created_at')
            ->limit($shortcode->limit ?: 4)
            ->get();

        if (setting('enable_faq_schema', false)) {
            $schemaItems = new FaqCollection();

            foreach ($faqs as $faq) {
                $schemaItems->push(new FaqItem($faq->question, $faq->answer));
            }

            app(FaqSupport::class)->registerSchema($schemaItems);
        }

        return Theme::partial('shortcodes.faqs.index', compact('shortcode', 'faqs'));
    });

    Shortcode::setPreviewImage('faqs', Theme::asset()->url('images/ui-blocks/faqs.png'));

    Shortcode::setAdminConfig('faqs', function (array $attributes) {
        $faqCategories = FaqCategory::query()
            ->wherePublished()
            ->orderByDesc('created_at')
            ->pluck('name', 'id')
            ->all();

        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
                    ->label(__('Description'))
            )
            ->add(
                'faq_category_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Choose Faq Categories'))
                    ->searchable()
                    ->multiple()
                    ->selected(explode(',', Arr::get($attributes, 'faq_category_ids')))
                    ->choices($faqCategories)
            )
            ->add(
                'limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Limit'))
                    ->defaultValue(4)
            )
            ->add(
                'button_secondary_label',
                TextField::class,
                TextFieldOption::make()
                    ->placeholder(__('Button Secondary Label'))
            )
            ->add(
                'button_secondary_url',
                TextField::class,
                TextFieldOption::make()
                    ->placeholder(__('Button Secondary URL'))
            )
            ->add(
                'button_primary_label',
                TextField::class,
                TextFieldOption::make()
                    ->placeholder(__('Button Primary Label'))
            )
            ->add(
                'button_primary_url',
                TextField::class,
                TextFieldOption::make()
                    ->placeholder(__('Button Primary URL'))
            )
        ;

    });

    Shortcode::register('faq-categories', __('FAQs Categories'), __('FAQs Categories'), function (ShortcodeCompiler $shortcode) {
        $faqCategories = FaqCategory::query()
            ->wherePublished()
            ->orderByDesc('created_at')
            ->limit($shortcode->limit ?? 8)
            ->get();

        return Theme::partial('shortcodes.faq-categories.index', compact('shortcode', 'faqCategories'));
    });

    Shortcode::setPreviewImage('faq-categories', Theme::asset()->url('images/ui-blocks/faq-categories.png'));

    Shortcode::setAdminConfig('faq-categories', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
                    ->label(__('Description'))
            )
            ->add(
                'limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Limit'))
                    ->defaultValue(4)
            );

    });
});
