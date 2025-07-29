<?php

namespace Botble\CarRentals\Repositories\Interfaces;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface CarCategoryInterface extends RepositoryInterface
{
    public function getCarCategories(
        array $select,
        array $orderBy,
        array $conditions = ['status' => BaseStatusEnum::PUBLISHED]
    ): Collection;

    public function getAllCategoriesWithChildren(
        array $condition = [],
        array $with = [],
        array $select = ['*']
    ): Collection;
}
