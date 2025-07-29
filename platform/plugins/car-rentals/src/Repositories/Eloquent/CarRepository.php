<?php

namespace Botble\CarRentals\Repositories\Eloquent;

use Botble\CarRentals\Facades\CarListHelper;
use Botble\CarRentals\Repositories\Interfaces\CarInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CarRepository extends RepositoriesAbstract implements CarInterface
{
    public function getCars(array $filters = [], array $params = [])
    {
        $filters = CarListHelper::getCarFilters($filters);
        $filters = array_merge([
            'rental_rate_from' => null,
            'rental_rate_to' => null,
            'start_date' => null,
            'end_date' => null,
            'car_types' => null,
            'car_fuel_types' => null,
            'car_review_scores' => null,
            'car_booking_locations' => null,
            'location' => null,
        ], $filters);

        $with = [
            'slugable',
            'transmission',
            'fuel',
            'pickupAddress',
            'make',
            'reviews',
        ];

        $orderBy = $params['order_by'] ?? 'recently_added';
        unset($params['order_by']);

        $params = array_merge([
            'condition' => [],
            'order_by' => [],
            'take' => null,
            'paginate' => [
                'per_page' => 20,
                'current_paged' => 1,
            ],
            'select' => ['cr_cars.*'],
            'with' => $with,
        ], $params);

        $this->model = $this->originalModel;

        $this->model = $this->model->filterCars($filters);
        $this->model = $this->model->sortCars($orderBy);

        return $this->advancedGet($params);
    }
}
