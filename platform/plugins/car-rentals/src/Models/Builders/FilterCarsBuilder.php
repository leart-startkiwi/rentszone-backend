<?php

namespace Botble\CarRentals\Models\Builders;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\Location\Facades\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FilterCarsBuilder extends BaseQueryBuilder
{
    public function filterCars(array $filters = []): self
    {
        if (! is_in_admin()) {
            $enabledPostApproval = CarRentalsHelper::isEnabledPostApproval();

            if ($enabledPostApproval) {
                $this->where('moderation_status', ModerationStatusEnum::APPROVED);
            }
        }

        $filters = [
            'rental_rate_from' => null,
            'rental_rate_to' => null,
            'start_date' => null,
            'end_date' => null,
            'location' => null,
            'car_categories' => [],
            'car_types' => [],
            'car_transmissions' => [],
            'car_fuel_types' => [],
            'car_review_scores' => [],
            'car_booking_locations' => [],
            'car_amenities' => [],
            'car_make' => null,
            'page' => null,
            'per_page' => null,
             ...$filters,
        ];

        $carCategories = Arr::get($filters, 'car_categories', []);

        $carCategories = $carCategories ? array_map('intval', array_filter($carCategories)) : [];

        if ($carCategories) {
            $this->whereHas('categories', function (Builder $query) use ($carCategories) {
                return $query->whereIn('cr_car_categories.id', $carCategories);
            });
        }

        $carColors = Arr::get($filters, 'car_colors', []);

        $carColors = $carColors ? array_map('intval', array_filter($carColors)) : [];

        if ($carColors) {
            $this->whereHas('colors', function (Builder $query) use ($carColors) {
                return $query->whereIn('cr_car_colors.id', $carColors);
            });
        }

        $carTypes = Arr::get($filters, 'car_types', []);

        $carTypes = $carTypes ? array_map('intval', array_filter($carTypes)) : [];

        if ($carTypes) {
            $this->whereIn('vehicle_type_id', $carTypes);
        }

        $carTransmissions = Arr::get($filters, 'car_transmissions', []);

        $carTransmissions = $carTransmissions ? array_map('intval', array_filter($carTransmissions)) : [];

        if ($carTransmissions) {
            $this->whereIn('transmission_id', $carTransmissions);
        }

        $carStatus = Arr::get($filters, 'adv_type');

        if (in_array($carStatus, ['used_car', 'new_car'])) {
            $this->where('is_used', $carStatus === 'used_car');
        }

        $carFuelTypes = Arr::get($filters, 'car_fuel_types', []);

        $carFuelTypes = $carFuelTypes ? array_map('intval', array_filter($carFuelTypes)) : [];

        if ($carFuelTypes) {
            $this->whereIn('fuel_type_id', $carFuelTypes);
        }

        $carBookingLocations = Arr::get($filters, 'car_booking_locations', []);

        $carBookingLocations = $carBookingLocations ? array_map('intval', array_filter($carBookingLocations)) : [];

        if ($carBookingLocations) {
            $this->whereIn('pick_address_id', $carBookingLocations);
        }

        $carReviewScores = Arr::get($filters, 'car_review_scores', []);

        $carReviewScores = $carReviewScores ? array_map('intval', array_filter($carReviewScores)) : [];

        if ($carReviewScores) {
            $this->whereHas('reviews', function (Builder $query) use ($carReviewScores) {
                return $query->whereIn('cr_car_reviews.star', $carReviewScores);
            });
        }

        $rentalRateFrom = Arr::get($filters, 'rental_rate_from');

        if ($rentalRateFrom && $rentalRateFrom > 0) {
            $this->where(function ($query) use ($rentalRateFrom): void {
                $query->whereNull('rental_rate')
                    ->orWhere('rental_rate', '>=', $rentalRateFrom);
            });
        }

        $rentalRateTo = Arr::get($filters, 'rental_rate_to');

        if ($rentalRateTo && $rentalRateTo > 0) {
            $this->where(function ($query) use ($rentalRateTo): void {
                $query->whereNull('rental_rate')
                    ->orWhere('rental_rate', '<=', $rentalRateTo);
            });
        }

        $carAmenities = Arr::get($filters, 'car_amenities', []);

        $carAmenities = $carAmenities ? array_map('intval', array_filter($carAmenities)) : [];

        if ($carAmenities) {
            $this->whereHas('amenities', function (Builder $query) use ($carAmenities) {
                return $query->whereIn('cr_car_amenities.id', $carAmenities);
            });
        }

        $carMake = Arr::get($filters, 'car_make', 0);

        if ($carMake) {
            $this->where('make_id', $carMake);
        }

        if (
            is_plugin_active('location')
            && ((int) Arr::get($filters, 'city_id') || (int) Arr::get($filters, 'state_id') || Arr::get($filters, 'location'))
        ) {
            $model = $this;
            if ($model instanceof BaseQueryBuilder) {
                $className = get_class($model->getModel());
            } else {
                $className = get_class($model);
            }

            $cityId = Arr::get($filters, 'city_id');
            $stateId = Arr::get($filters, 'state_id');
            $location = Arr::get($filters, 'location');

            if (Location::isSupported($className)) {
                if ($cityId) {
                    $model->whereHas('pickupAddress', function ($query) use ($cityId) {
                        return $query->where('city_id', $cityId);
                    });
                } elseif ($stateId) {
                    $model->whereHas('pickupAddress', function ($query) use ($cityId) {
                        return $query->where('state_id', $cityId);
                    });
                } elseif ($location) {
                    $locationData = explode(',', $location);

                    if (count($locationData) > 1) {
                        $model
                            ->leftJoin('cr_car_addresses', 'cr_cars.pick_address_id', '=', 'cr_car_addresses.id')
                            ->leftJoin('cities as c', 'c.id', '=', 'cr_car_addresses.city_id')
                            ->leftJoin('states', 'states.id', '=', 'cr_car_addresses.state_id')
                            ->where('c.name', 'LIKE', '%' . $location . '%')
                            ->orWhere('states.name', 'LIKE', '%' . $location . '%');
                    } else {
                        $model
                            ->leftJoin('cr_car_addresses', 'cr_cars.pick_address_id', '=', 'cr_car_addresses.id')
                            ->leftJoin('cities as c', 'c.id', '=', 'cr_car_addresses.city_id')
                            ->leftJoin('states', 'states.id', '=', 'cr_car_addresses.state_id')
                            ->leftJoin('countries', 'countries.id', '=', 'cr_car_addresses.country_id')
                            ->where('c.name', 'LIKE', '%' . $location . '%')
                            ->orWhere('states.name', 'LIKE', '%' . $location . '%')
                            ->orWhere('countries.name', 'LIKE', '%' . $location . '%');
                    }
                }
            }
        }

        if ($filters['start_date'] || $filters['end_date']) {
            $this->whereAvailableAt($filters);
        }

        return $this;
    }

    public function sortCars(string $orderBy): self
    {
        if (! is_in_admin()) {
            $enabledPostApproval = CarRentalsHelper::isEnabledPostApproval();

            if ($enabledPostApproval) {
                $this->where('moderation_status', ModerationStatusEnum::APPROVED);
            }
        }

        switch ($orderBy) {
            case 'most_popular':
                $this->orderBy('created_at');

                break;
            case 'top_rated':
                $this->leftJoin('cr_car_reviews', 'cr_cars.id', '=', 'cr_car_reviews.car_id')
                    ->groupBy('cr_cars.id')->latest(DB::raw('AVG(cr_car_reviews.star)'));

                break;
            case 'most_viewed':
                $this->leftJoin('cr_car_views', 'cr_cars.id', '=', 'cr_car_views.car_id')
                    ->groupBy('cr_cars.id')->latest(DB::raw('AVG(cr_car_views.views)'));

                break;
            default:
                $this->orderBy('created_at', 'desc');
        }

        return $this;
    }
}
