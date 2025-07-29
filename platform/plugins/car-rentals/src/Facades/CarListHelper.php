<?php

namespace Botble\CarRentals\Facades;

use Botble\CarRentals\Supports\CarListHelper as CarListHelperSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array dataForFilter()
 * @method static array getCarFilters(\Illuminate\Http\Request|array $inputs)
 * @method static \Illuminate\Database\Eloquent\Collection carCategoriesForFilter()
 * @method static \Illuminate\Database\Eloquent\Collection carColorsForFilter()
 * @method static \Illuminate\Database\Eloquent\Collection carTypesForFilter()
 * @method static \Illuminate\Database\Eloquent\Collection carTransmissionsForFilter()
 * @method static \Illuminate\Database\Eloquent\Collection carFuelTypesForFilter()
 * @method static \Illuminate\Database\Eloquent\Collection carBookingLocationsForFilter()
 * @method static \Illuminate\Database\Eloquent\Collection carReviewScoresForFilter()
 * @method static int getCarMaxRentalRate()
 * @method static array getPerPageParams()
 * @method static array getSortByParams()
 * @method static array getAdvancedTypes()
 * @method static \Illuminate\Database\Eloquent\Collection carAmenitiesForFilter()
 *
 * @see \Botble\CarRentals\Supports\CarListHelper
 */
class CarListHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CarListHelperSupport::class;
    }
}
