<?php

namespace Botble\CarRentals\Supports;

use Botble\CarRentals\Enums\CommissionFeeTypeEnum;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Tax;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CarRentalsHelper
{
    public function isEnabledEmailVerification(): bool
    {
        return (bool) get_car_rentals_setting('verify_customer_email', false);
    }

    public function isEnabledCustomerRegistration(): bool
    {
        return (bool) get_car_rentals_setting('enabled_customer_registration', true);
    }

    public function getBookingNumber(string|int $id): string
    {
        $prefix = get_car_rentals_setting('booking_number_prefix') ? get_car_rentals_setting('booking_number_prefix') . '-' : '';
        $suffix = get_car_rentals_setting('booking_number_suffix') ? '-' . get_car_rentals_setting('booking_number_suffix') : '';

        return sprintf(
            '#%s%d%s',
            $prefix,
            (int) config('plugins.car-rentals.car-rentals.default_number_start_number') + $id,
            $suffix
        );
    }

    public function getDateRangeInReport(Request $request): array
    {
        $startDate = Carbon::now()->subDays(29);
        $endDate = Carbon::now();

        if ($request->input('date_from')) {
            try {
                $startDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_from'));
            } catch (Exception) {
                $startDate = Carbon::now()->subDays(29);
            }
        }

        if ($request->input('date_to')) {
            try {
                $endDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_to'));
            } catch (Exception) {
                $endDate = Carbon::now();
            }
        }

        if ($endDate->gt(Carbon::now())) {
            $endDate = Carbon::now();
        }

        if ($startDate->gt($endDate)) {
            $startDate = Carbon::now()->subDays(29);
        }

        $predefinedRange = $request->input('predefined_range', trans('plugins/car-rentals::booking-report.ranges.last_30_days'));

        return [$startDate, $endDate, $predefinedRange];
    }

    public function dateFromRequest(string $date): Carbon|false
    {
        return Carbon::createFromFormat($this->getDateFormat(), $date);
    }

    public function getDateFormat(): string
    {
        return 'Y-m-d';
    }

    public function getLocations(): array
    {
        if (! is_plugin_active('location')) {
            return [];
        }

        $filterBy = theme_option('car_location_filter_by', 'state');

        $isDisplayCountry = theme_option('car_location_is_display_country', true);

        if ($filterBy === 'state') {
            $states = State::query()
                ->wherePublished()
                ->orderBy('order')
                ->orderBy('name')
                ->get();

            if ($isDisplayCountry) {
                $states->loadMissing('country');

                return $states->mapWithKeys(function ($state) {
                    return [$state->id => implode(', ', array_filter([$state->name, $state->country->name]))];
                })->all();
            }

            return $states->mapWithKeys(function ($state) {
                return [$state->id => $state->name];
            })->all();
        }

        $cities = City::query()
            ->wherePublished()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        if ($isDisplayCountry) {
            $cities->loadMissing(['country', 'state']);

            return $cities->mapWithKeys(function ($city) {
                return [$city->id => implode(', ', array_filter([$city->name . ($city->zip_code ? ' ' . $city->zip_code : null), $city->state->name, $city->country->name]))];
            })->all();
        }

        $cities->loadMissing(['state']);

        return $cities->mapWithKeys(function ($city) {
            return [$city->id => implode(', ', array_filter([$city->name . ($city->zip_code ? ' ' . $city->zip_code : null), $city->state->name]))];
        })->all();
    }

    public function isTaxEnabled(): bool
    {
        return (bool) get_car_rentals_setting('tax_enabled', false);
    }

    public function getAppliedTaxes(): Collection
    {
        if (! $this->isTaxEnabled()) {
            return collect();
        }

        $taxIds = get_car_rentals_setting('tax_active_ids', []);

        if (! $taxIds) {
            return collect();
        }

        $taxIds = json_decode($taxIds, true);

        if (! $taxIds) {
            return collect();
        }

        return Tax::query()
            ->whereIn('id', $taxIds)
            ->wherePublished()
            ->get();
    }

    public function getTaxAmount(float $amount): float
    {
        $taxes = $this->getAppliedTaxes();

        if (! $taxes) {
            return 0;
        }

        $totalTax = 0;

        foreach ($taxes as $tax) {
            $totalTax += $tax->percentage * $amount / 100;
        }

        return $totalTax;
    }

    public function isEnabledCarReviews(): bool
    {
        return (bool) get_car_rentals_setting('enabled_review', true);
    }

    public function getSetting(string $key, string|int|array|null|bool $default = ''): string|int|array|null|bool
    {
        return get_car_rentals_setting('' . $key, $default);
    }

    public function isCommissionCategoryFeeBasedEnabled(): bool
    {
        return (bool) $this->getSetting('enable_commission_fee_for_each_category', false);
    }

    public function getCommissionFeeType(): string
    {
        return $this->getSetting('commission_fee_type', CommissionFeeTypeEnum::PERCENTAGE);
    }

    public function getCommissionFee(): float
    {
        return (float) $this->getSetting('rental_commission_fee', 0);
    }

    public function calculateCommissionFee(float $price): float
    {
        $fee = $this->getCommissionFee();

        if ($this->getCommissionFeeType() === CommissionFeeTypeEnum::PERCENTAGE) {
            return $price * ($fee / 100);
        }

        return $fee;
    }

    public function getMinimumWithdrawalAmount(): float
    {
        return (float) $this->getSetting('minimum_withdrawal_amount', 0);
    }

    public function calculateCategoryCommissionFee(float $price, int $categoryId): float
    {
        if (! $this->isCommissionCategoryFeeBasedEnabled()) {
            return $this->calculateCommissionFee($price);
        }

        $categoryCommission = DB::table('cr_category_commissions')
            ->where('car_category_id', $categoryId)
            ->first();

        if (! $categoryCommission) {
            return $this->calculateCommissionFee($price);
        }

        $fee = $categoryCommission->commission_percentage;

        if ($this->getCommissionFeeType() === CommissionFeeTypeEnum::PERCENTAGE) {
            return $price * ($fee / 100);
        }

        return $fee;
    }

    public function calculateCarCommissionFee(float $price, int $carId): float
    {
        if (! $this->isCommissionCategoryFeeBasedEnabled()) {
            return $this->calculateCommissionFee($price);
        }

        // Get car categories
        $carCategories = DB::table('cr_cars_categories')
            ->where('cr_car_id', $carId)
            ->pluck('cr_car_category_id')
            ->toArray();

        if (empty($carCategories)) {
            return $this->calculateCommissionFee($price);
        }

        // Get category commissions
        $categoryCommissions = DB::table('cr_category_commissions')
            ->whereIn('car_category_id', $carCategories)
            ->get();

        if ($categoryCommissions->isEmpty()) {
            return $this->calculateCommissionFee($price);
        }

        // Get the highest commission percentage
        $highestCommission = $categoryCommissions->max('commission_percentage');

        if ($this->getCommissionFeeType() === CommissionFeeTypeEnum::PERCENTAGE) {
            return $price * ($highestCommission / 100);
        }

        return $highestCommission;
    }

    public function getCarCommissionInfo(int $carId): array
    {
        $result = [
            'commission_fee' => $this->getCommissionFee(),
            'commission_type' => $this->getCommissionFeeType(),
            'is_category_based' => false,
            'category_commission' => null,
        ];

        if (! $this->isCommissionCategoryFeeBasedEnabled()) {
            return $result;
        }

        // Get car categories
        $carCategories = DB::table('cr_cars_categories')
            ->where('cr_car_id', $carId)
            ->pluck('cr_car_category_id')
            ->toArray();

        if (empty($carCategories)) {
            return $result;
        }

        // Get category commissions
        $categoryCommissions = DB::table('cr_category_commissions')
            ->whereIn('car_category_id', $carCategories)
            ->get();

        if ($categoryCommissions->isEmpty()) {
            return $result;
        }

        // Get the highest commission percentage
        $highestCommission = $categoryCommissions->sortByDesc('commission_percentage')->first();

        if (! $highestCommission) {
            return $result;
        }

        // Get category name
        $category = DB::table('cr_car_categories')
            ->where('id', $highestCommission->car_category_id)
            ->first();

        $result['is_category_based'] = true;
        $result['category_commission'] = [
            'percentage' => $highestCommission->commission_percentage,
            'category_id' => $highestCommission->car_category_id,
            'category_name' => $category ? $category->name : 'Unknown',
        ];

        return $result;
    }

    public function formatCommissionInfo(array $commissionInfo): string
    {
        if ($commissionInfo['is_category_based'] && $commissionInfo['category_commission']) {
            $categoryCommission = $commissionInfo['category_commission'];
            $percentage = $categoryCommission['percentage'];
            $categoryName = $categoryCommission['category_name'];

            if ($commissionInfo['commission_type'] === CommissionFeeTypeEnum::PERCENTAGE) {
                return sprintf('%s%% (%s)', $percentage, $categoryName);
            }

            return sprintf('%s (%s)', format_price($percentage), $categoryName);
        }

        $fee = $commissionInfo['commission_fee'];

        if ($commissionInfo['commission_type'] === CommissionFeeTypeEnum::PERCENTAGE) {
            return sprintf('%s%%', $fee);
        }

        return format_price($fee);
    }

    public function getReviewsGroupedByCarId(int|string $carId, int $reviewsCount = 0): Collection
    {
        if ($reviewsCount) {
            $reviews = CarReview::query()
                ->select([DB::raw('COUNT(star) as star_count'), 'star'])
                ->where('car_id', $carId)
                ->wherePublished()
                ->groupBy('star')
                ->get();
        } else {
            $reviews = collect();
        }

        $results = collect();
        for ($i = 5; $i >= 1; $i--) {
            if ($reviewsCount) {
                $review = $reviews->firstWhere('star', $i);
                $starCount = $review ? $review->star_count : 0;
                if ($starCount > 0) {
                    $starCount = $starCount / $reviewsCount * 100;
                }
            } else {
                $starCount = 0;
            }

            $results[] = [
                'star' => $i,
                'count' => $starCount,
                'percent' => ((int) ($starCount * 100)) / 100,
            ];
        }

        return $results;
    }

    public function getAssetVersion(): string
    {
        return '1.1.0';
    }

    public function view(string $view, array $data = [])
    {
        return view($this->viewPath($view), $data);
    }

    public function viewPath(string $view): string
    {
        $themeView = Theme::getThemeNamespace() . '::views.car-rentals.' . $view;

        if (view()->exists($themeView)) {
            return $themeView;
        }

        return 'plugins/car-rentals::themes.' . $view;
    }

    public function getCarsFilterKeys(): array
    {
        return [
            'vehicle_condition',
            'locations',
            'prices',
            'categories',
            'colors',
            'types',
            'transmissions',
            'fuels',
            'review_scores',
            'addresses',
        ];
    }

    public function getCarsFilterBy(): array
    {
        $default = $this->getCarsFilterKeys();

        $setting = get_car_rentals_setting('filter_cars_by', []);

        if ($setting && ! is_array($setting)) {
            $setting = json_decode($setting, true);
        }

        if (! $setting) {
            return $default;
        }

        return $setting;
    }

    public function isEnabledFilterCarsBy(string $key): bool
    {
        if (! $this->isEnabledCarFilter()) {
            return false;
        }

        $filterKeys = $this->getCarsFilterKeys();

        if (! in_array($key, $filterKeys)) {
            return false;
        }

        return in_array($key, $this->getCarsFilterBy());
    }

    public function isEnabledCarFilter(): bool
    {
        return get_car_rentals_setting('enabled_car_filter', true);
    }

    public function isMultiVendorEnabled(): bool
    {
        return (bool) get_car_rentals_setting('enabled_multi_vendor', false);
    }

    public function isRentalBookingEnabled(): bool
    {
        return (bool) get_car_rentals_setting('enabled_car_rental', true);
    }

    public function maxFilesizeUploadByVendor(): int
    {
        $size = get_car_rentals_setting('max_filesize_upload_by_vendor');

        if (! $size) {
            $size = setting('max_upload_filesize') ?: 10;
        }

        return (int) $size;
    }

    public function maxPostImagesUploadByVendor(): int
    {
        return (int) get_car_rentals_setting('max_post_images_upload_by_vendor', 20);
    }

    public function isEnabledCarRental(): bool
    {
        return (bool) CarRentalsHelper::isRentalBookingEnabled();
    }

    public function isEnabledPostApproval(): bool
    {
        return (bool) get_car_rentals_setting('enabled_post_approval', true);
    }
}
