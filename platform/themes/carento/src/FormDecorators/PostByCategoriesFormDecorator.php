<?php

namespace Theme\Carento\FormDecorators;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Blog\Models\Category;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Arr;

class PostByCategoriesFormDecorator
{
    public static function createFrom(
        WidgetForm|ShortcodeForm $form,
        ?string $addAfter = null
    ): WidgetForm|ShortcodeForm {
        if (! is_plugin_active('blog')) {
            return $form;
        }

        $categories = Category::query()->pluck('name', 'id')->all();
        $data = $form->getModel();
        $categoryIds = Arr::get($data, 'category_ids');

        if (! is_array($categoryIds)) {
            $categoryIds = $categoryIds ? explode(',', Arr::get($data, 'category_ids')) : null;
        }

        $params = [
            'category_ids',
            SelectField::class,
            SelectFieldOption::make()
                ->label(__('Choose categories'))
                ->choices($categories)
                ->selected($categoryIds)
                ->searchable()
                ->multiple(),
        ];

        if ($addAfter) {
            $params = [$addAfter, ...$params];
        }

        $form->{$addAfter ? 'addAfter' : 'add'}(...$params);

        return $form;
    }
}
