<?php

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Blog\Models\Category;
use Botble\Theme\Facades\Theme;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Theme\Carento\FormDecorators\PostByCategoriesFormDecorator;
use Theme\Carento\FormDecorators\PostFormDecorator;
use Theme\Carento\Support\ThemeHelper;

class FeaturedPostsWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Featured Posts'),
            'description' => __('Choose type and categories for posts in widget'),
        ]);
    }

    protected function data(): array|Collection
    {
        $config = $this->getConfig();

        $categoryIds = Arr::get($config, 'category_ids', []) ?: [];
        $limit = (int) Arr::get($config, 'limit', 4);
        $types = Arr::get($config, 'types');

        $categories = Category::query()->whereIn('id', $categoryIds)->get();

        $posts = ThemeHelper::getBlogPosts($categoryIds, (is_array($types) ? Arr::first($types) : $types), $limit);

        return compact('posts', 'categories');
    }

    public function settingForm(): WidgetForm|string|null
    {
        $form = WidgetForm::createFromArray($this->getConfig())
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 2))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/widgets/featured-posts/$style.png"),
                                ],
                            ])
                            ->all()
                    )
                    ->selected(Arr::get($this->getConfig(), 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
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
