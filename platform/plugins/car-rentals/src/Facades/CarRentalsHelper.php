<?php

namespace Botble\CarRentals\Facades;

use Botble\CarRentals\Supports\CarRentalsHelper as CarRentalsSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isEnabledEmailVerification()
 * @method static bool isEnabledCustomerRegistration()
 * @method static string getBookingNumber(string|int $id)
 * @method static \Carbon\Carbon|false dateFromRequest(string $date)
 * @method static string getDateFormat()
 * @method static array getLocations()
 * @method static bool isTaxEnabled()
 * @method static \Illuminate\Support\Collection getAppliedTaxes()
 * @method static float getTaxAmount(float $amount)
 * @method static bool isEnabledCarReviews()
 * @method static \Illuminate\Support\Collection getReviewsGroupedByCarId(string|int $carId, int $reviewsCount = 0)
 * @method static string viewPath(string $view)
 * @method static array getCarsFilterKeys()
 * @method static array getCarsFilterBy()
 * @method static bool isEnabledFilterCarsBy(string $key)
 * @method static bool isEnabledCarFilter()
 * @method static bool isMultiVendorEnabled()
 * @method static bool isRentalBookingEnabled()
 * @method static string|int|array|null|bool getSetting(string $key, string|int|array|null|bool $default = '')
 * @method static bool isCommissionCategoryFeeBasedEnabled()
 * @method static string getCommissionFeeType()
 * @method static float getCommissionFee()
 * @method static float calculateCommissionFee(float $price)
 * @method static float calculateCategoryCommissionFee(float $price, int $categoryId)
 * @method static float calculateCarCommissionFee(float $price, int $carId)
 * @method static array getCarCommissionInfo(int $carId)
 * @method static string formatCommissionInfo(array $commissionInfo)
 * @method static float getMinimumWithdrawalAmount()
 * @method static array getDateRangeInReport(\Illuminate\Http\Request $request)
 * @method static int maxFilesizeUploadByVendor()
 * @method static int maxPostImagesUploadByVendor()
 * @method static bool isEnabledCarRental()
 * @method static bool isEnabledPostApproval()
 *
 * @see \Botble\CarRentals\Supports\CarRentalsHelper
 */
class CarRentalsHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CarRentalsSupport::class;
    }
}
