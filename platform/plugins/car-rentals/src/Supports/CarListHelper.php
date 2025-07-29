<?php

namespace Botble\CarRentals\Supports;

use Botble\Base\Facades\BaseHelper;
use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper as CarRentalsHelperFacade;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Models\CarAmenity;
use Botble\CarRentals\Models\CarCategory;
use Botble\CarRentals\Models\CarColor;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\CarTransmission;
use Botble\CarRentals\Models\CarType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CarListHelper
{
    public function dataForFilter(): array
    {
        $collectionEmpty = new Collection();

        return [
            CarRentalsHelperFacade::isEnabledFilterCarsBy('categories') ? $this->carCategoriesForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('colors') ? $this->carColorsForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('types') ? $this->carTypesForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('transmissions') ? $this->carTransmissionsForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('fuels') ? $this->carFuelTypesForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('addresses') ? $this->carBookingLocationsForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('review_scores') ? $this->carReviewScoresForFilter() : $collectionEmpty,
            CarRentalsHelperFacade::isEnabledFilterCarsBy('prices') ? $this->getCarMaxRentalRate() : null,
            $this->carAmenitiesForFilter(),
            $this->getAdvancedTypes(),
        ];
    }

    public function getCarFilters(array|Request $inputs): array
    {
        if ($inputs instanceof Request) {
            $inputs = $inputs->input();
        }

        $params = [
            'rental_rate_from' => BaseHelper::stringify(Arr::get($inputs, 'rental_rate_from')),
            'rental_rate_to' => BaseHelper::stringify(Arr::get($inputs, 'rental_rate_to')),
            'start_date' => BaseHelper::stringify(Arr::get($inputs, 'start_date')),
            'end_date' => BaseHelper::stringify(Arr::get($inputs, 'end_date')),
            'location' => BaseHelper::stringify(Arr::get($inputs, 'location')),
            'car_categories' => (array) Arr::get($inputs, 'car_categories', []),
            'car_types' => (array) Arr::get($inputs, 'car_types', []),
            'car_colors' => (array) Arr::get($inputs, 'car_colors', []),
            'car_transmissions' => (array) Arr::get($inputs, 'car_transmissions', []),
            'car_fuel_types' => (array) Arr::get($inputs, 'car_fuel_types', []),
            'car_review_scores' => (array) Arr::get($inputs, 'car_review_scores', []),
            'car_booking_locations' => (array) Arr::get($inputs, 'car_booking_locations', []),
            'car_amenities' => (array) Arr::get($inputs, 'car_amenities', []),
            'car_make' => (int) Arr::get($inputs, 'car_make', 0),
            'page' => (int) Arr::get($inputs, 'page', 1),
            'per_page' => (int) Arr::get($inputs, 'per_page', 12),
            'sort_by' => Arr::get($inputs, 'sort_by', ''),
            'adv_type' => Arr::get($inputs, 'adv_type', ''),
        ];

        $dateFormat = CarRentalsHelperFacade::getDateFormat();

        $validator = Validator::make($params, [
            'rental_rate_from' => ['nullable', 'numeric'],
            'rental_rate_to' => ['nullable', 'numeric'],
            'start_date' => ['nullable', 'string', 'date', 'date_format:' . $dateFormat, 'after_or_equal:today'],
            'end_date' => ['nullable', 'string', 'date', 'date_format:' . $dateFormat, 'after_or_equal:start_date'],
            'location' => 'nullable|string',
            'car_categories' => ['nullable', 'array'],
            'car_colors' => ['nullable', 'array'],
            'car_types' => ['nullable', 'array'],
            'car_transmissions' => ['nullable', 'array'],
            'car_fuel_types' => ['nullable', 'array'],
            'car_review_scores' => ['nullable', 'array'],
            'car_booking_locations' => ['nullable', 'array'],
            'car_amenities' => ['nullable', 'array'],
            'car_make' => ['nullable', 'exists:cr_cars,make_id'],
            'page' => ['nullable', 'integer', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:0'],
            'sort_by' => ['nullable', Rule::in(array_keys($this->getSortByParams()))],
            'adv_type' => ['nullable', Rule::in(array_keys($this->getAdvancedTypes()))],
        ]);

        return $validator->valid();
    }

    public function carCategoriesForFilter(): Collection
    {
        return CarCategory::query()
            ->wherePublished()
            ->withCount('activeCars as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get()
            ->where('cars_count', '>', 0);
    }

    public function carColorsForFilter(): Collection
    {
        return CarColor::query()
            ->wherePublished()
            ->withCount('activeCars as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get()
            ->where('cars_count', '>', 0);
    }

    public function carTypesForFilter(): Collection
    {
        return CarType::query()
            ->wherePublished()
            ->withCount('activeCar as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get();
    }

    public function carTransmissionsForFilter(): Collection
    {
        return CarTransmission::query()
            ->wherePublished()
            ->withCount('activeCar as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get();
    }

    public function carFuelTypesForFilter(): Collection
    {
        return CarFuel::query()
            ->wherePublished()
            ->withCount('activeCar as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get()
            ->where('cars_count', '>', 0);
    }

    public function carBookingLocationsForFilter(): Collection
    {
        return CarAddress::query()
            ->wherePublished()
            ->withCount('activeCar as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get();
    }

    public function carReviewScoresForFilter(): Collection
    {
        return CarReview::query()
            ->select('star', DB::raw('count(*) as cars_count'))
            ->groupBy('star')->latest('star')
            ->get();
    }

    public function getCarMaxRentalRate(): int
    {
        return Cache::remember('car_list_car_max_rental_rate', Carbon::now()->addHour(), function (): int {
            $rentalRate = Car::query()
                ->where('status', CarStatusEnum::AVAILABLE)
                ->max('rental_rate');

            return $rentalRate ? (int) ceil($rentalRate) : 0;
        });
    }

    public function getPerPageParams(): array
    {
        return [20, 30, 50];
    }

    public function getSortByParams(): array
    {
        return apply_filters('car_list_sort_by_params', [
            'recently_added' => __('Recently added'),
            'most_popular' => __('Most popular'),
            'top_rated' => __('Top rated'),
            'most_viewed' => __('Most Viewed'),
        ]);
    }

    public function getAdvancedTypes(): array
    {
        return [
            'all' => __('All'),
            'new_car' => __('New'),
            'used_car' => __('Used'),
        ];
    }

    public function carAmenitiesForFilter(): Collection
    {
        return CarAmenity::query()
            ->wherePublished()
            ->withCount('activeCars as cars_count')
            ->orderByDesc('cars_count')->latest()
            ->get()
            ->where('cars_count', '>', 0);
    }
}
