<?php

namespace Botble\CarRentals\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\CarRentals\Repositories\Interfaces\CarCategoryInterface;
use Illuminate\Support\Arr;

class GetCategoriesService
{
    public function execute(array $args = []): array
    {
        $indent = Arr::get($args, 'indent', '——');

        /** @var CarCategoryInterface $repo */
        $repo = app(CarCategoryInterface::class);

        $categories = $repo->getCarCategories(Arr::get($args, 'select', ['*']), [
            'is_default' => 'DESC',
            'order' => 'ASC',
            'created_at' => 'DESC',
        ], Arr::get($args, 'condition', ['status' => BaseStatusEnum::PUBLISHED]));

        $categories = sort_item_with_children($categories);

        foreach ($categories as $category) {
            $depth = (int) $category->depth;
            $indentText = str_repeat($indent, $depth);
            $category->indent_text = $indentText;
        }

        return $categories;
    }
}
