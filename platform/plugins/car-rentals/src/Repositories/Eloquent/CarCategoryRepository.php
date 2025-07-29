<?php

namespace Botble\CarRentals\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\CarRentals\Repositories\Interfaces\CarCategoryInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;

class CarCategoryRepository extends RepositoriesAbstract implements CarCategoryInterface
{
    public function getCarCategories(
        array $select,
        array $orderBy,
        array $conditions = ['status' => BaseStatusEnum::PUBLISHED]
    ): Collection {
        $data = $this->model
            ->with('slugable')
            ->select($select);

        if ($conditions) {
            $data = $data->where($conditions);
        }

        foreach ($orderBy as $by => $direction) {
            $data = $data->oldest($by);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAllCategoriesWithChildren(
        array $condition = [],
        array $with = [],
        array $select = ['*']
    ): Collection {
        $data = $this->model
            ->where($condition)
            ->with($with)
            ->select($select);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
