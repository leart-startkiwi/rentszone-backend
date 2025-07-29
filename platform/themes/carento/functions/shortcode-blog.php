<?php

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use FriendsOfBotble\Comment\Models\Comment;
use Illuminate\Support\Arr;

app()->booted(function (): void {
    if (! is_plugin_active('blog')) {
        return;
    }

    Shortcode::register('blog-posts', __('Blog Posts'), __('Blog Posts'), function (ShortcodeCompiler $shortcode): ?string {
        $limit = (int) $shortcode->limit ?: 4;
        $categoryIds = Shortcode::fields()->getIds('category_ids', $shortcode);

        $posts = Post::query()
            ->with('slugable')
            ->wherePublished()
            ->when(! empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                });
            })
            ->limit($limit)
            ->latest()
            ->get();

        if ($posts->isEmpty()) {
            return null;
        }

        $postCommentCount = Comment::query()
            ->selectRaw('reference_id, count(*) as count')
            ->where('reference_type', Post::class)
            ->whereIn('reference_id', $posts->pluck('id'))
            ->groupBy('reference_id')
            ->pluck('count', 'reference_id');

        return Theme::partial('shortcodes.blog-posts.index', compact('shortcode', 'posts', 'postCommentCount'));
    });
    Shortcode::setPreviewImage('blog-posts', Theme::asset()->url('images/shortcodes/blog-posts/style-1.png'));
    Shortcode::setAdminConfig('blog-posts', function (array $attributes) {
        $categories = Category::query()->pluck('name', 'id')->all();

        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 3))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/blog-posts/$style.png"),
                                ],
                            ])
                            ->all()
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
                'category_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Choose categories'))
                    ->choices($categories)
                    ->selected(explode(',', Arr::get($attributes, 'category_ids')))
                    ->searchable()
                    ->multiple(),
            )
            ->add(
                'limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Total display'))
                    ->max(60)
                    ->defaultValue(10)
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
                    ->defaultValue(__('Keep Reading'))
            )
            ->add(
                'link_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Link Label'))
            )
            ->add(
                'link_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Link URL'))
            );
    });
});
