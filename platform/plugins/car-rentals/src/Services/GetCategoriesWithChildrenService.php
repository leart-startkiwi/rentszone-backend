<?php

namespace Botble\CarRentals\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\SortItemsWithChildrenHelper;
use Botble\CarRentals\Repositories\Interfaces\CarCategoryInterface;

class GetCategoriesWithChildrenService
{
    public function execute(): array
    {
        /** @var CarCategoryInterface $repo */
        $repo = app(CarCategoryInterface::class);
        $categories = $repo->getAllCategoriesWithChildren(
            ['status' => BaseStatusEnum::PUBLISHED],
            [],
            ['id', 'name', 'parent_id']
        );

        /** @var SortItemsWithChildrenHelper $helper */
        $helper = app(SortItemsWithChildrenHelper::class);

        return $helper
            ->setChildrenProperty('child_cats')
            ->setItems($categories)
            ->sort();
    }
}
