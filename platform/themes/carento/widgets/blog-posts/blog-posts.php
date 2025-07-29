<?php

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Theme\Carento\FormDecorators\PostByCategoriesFormDecorator;
use Theme\Carento\FormDecorators\PostFormDecorator;
use Theme\Carento\Support\ThemeHelper;

class BlogPostsWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Blog Posts'),
            'description' => __('Choose type and categories for posts in widget'),
        ]);
    }

    protected function data(): array|Collection
    {
        $config = $this->getConfig();

        $categoryIds = Arr::get($config, 'category_ids', []) ?: [];
        $limit = (int) Arr::get($config, 'limit', 4);
        $types = Arr::get($config, 'types');

        $posts = ThemeHelper::getBlogPosts($categoryIds, (is_array($types) ? Arr::first($types) : $types), $limit);

        return compact('posts');
    }

    public function settingForm(): WidgetForm|string|null
    {
        $form = WidgetForm::createFromArray($this->getConfig())
            ->add(
                'types',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Choose type'))
                    ->choices([
                        'popular' => __('Popular posts'),
                        'featured' => __('Featured posts'),
                        'recent' => __('Recent posts'),
                    ])
                    ->searchable()
                    ->multiple()
            );

        return PostFormDecorator::createFrom(PostByCategoriesFormDecorator::createFrom($form));
    }

    protected function requiredPlugins(): array
    {
        return ['blog'];
    }
}
