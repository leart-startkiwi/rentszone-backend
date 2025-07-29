<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\CarRentals\Enums\CarConditionEnum;
use Botble\CarRentals\Enums\CarForSaleStatusEnum;
use Botble\CarRentals\Enums\CarRentalTypeEnum;
use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\Builders\FilterCarsBuilder;
use Botble\CarRentals\Models\Scopes\ApprovedCarScope;
use Botble\Faq\Models\Faq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Car extends BaseModel
{
    protected $table = 'cr_cars';

    protected $fillable = [
        'license_plate',
        'name',
        'description',
        'content',
        'images',
        'make_id',
        'vehicle_type_id',
        'transmission_id',
        'fuel_type_id',
        'number_of_seats',
        'number_of_doors',
        'year',
        'mileage',
        'rental_rate',
        'rental_type',
        'rental_available_types',
        'status',
        'insurance_info',
        'vin',
        'location',
        'pick_address_id',
        'return_address_id',
        'is_featured',
        'is_used',
        'author_id',
        'author_type',
        'is_for_sale',
        'sale_price',
        'condition',
        'ownership_history',
        'warranty_information',
        'insurance_info',
        'sale_status',
        'tax_id',
        'vendor_id',
        'external_booking_url',
    ];

    protected $casts = [
        'images' => 'array',
        'status' => CarStatusEnum::class,
        'rental_type' => CarRentalTypeEnum::class,
        'moderation_status' => ModerationStatusEnum::class,
        'sale_status' => CarForSaleStatusEnum::class,
        'condition' => CarConditionEnum::class,
        'rental_rate' => 'double',
        'sale_price' => 'double',
    ];

    protected $appends = [
        'avg_review',
        'price_text',
    ];

    public function make(): BelongsTo
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CarTag::class, 'cr_car_tag', 'car_id', 'tag_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CarReview::class, 'car_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CarCategory::class, 'cr_cars_categories', 'cr_car_id', 'cr_car_category_id');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(CarColor::class, 'cr_cars_colors', 'cr_car_id', 'cr_car_color_id');
    }

    public function serviceHistories(): HasMany
    {
        return $this->hasMany(CarMaintenanceHistory::class, 'car_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function pickupAddress(): HasOne
    {
        return $this->hasOne(CarAddress::class, 'id', 'pick_address_id');
    }

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(CarFuel::class, 'fuel_type_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CarType::class, 'vehicle_type_id');
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo(CarTransmission::class, 'transmission_id');
    }

    public function returnAddress(): HasOne
    {
        return $this->hasOne(CarAddress::class, 'id', 'return_address_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(CarAmenity::class, 'cr_cars_amenities', 'cr_car_id', 'cr_car_amenity_id');
    }

    public function author(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    protected function currentLocation(): Attribute
    {
        return Attribute::get(function () {
            if ($this->is_for_sale) {
                return $this->location;
            }

            return $this->pickupAddress->full_address ?? $this->location;
        });
    }

    protected function pickupAddressText(): Attribute
    {
        return Attribute::get(function () {
            return $this->pickupAddress->full_address ?? '';
        });
    }

    protected function returnAddressText(): Attribute
    {
        return Attribute::get(function () {
            return $this->returnAddress->full_address ?? '';
        });
    }

    public function isAvailableAt(array $filters = []): bool
    {
        if (empty($filters['start_date']) || empty($filters['end_date'])) {
            return true;
        }

        $dateFormat = CarRentalsHelper::getDateFormat();

        $allDates = [];

        for (
            $index = strtotime($filters['start_date']);
            $index <= strtotime($filters['end_date']);
            $index += 60 * 60 * 24
        ) {
            $allDates[] = date($dateFormat, $index);
        }

        $carBookings = $this->activeBookingCars;

        if ($carBookings->isNotEmpty()) {
            foreach ($carBookings as $carBooking) {
                for (
                    $index = strtotime($carBooking->rental_start_date);
                    $index < strtotime($carBooking->rental_end_date);
                    $index += 60 * 60 * 24
                ) {
                    if (in_array(date($dateFormat, $index), $allDates)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function activeBookingCars(): HasMany
    {
        return $this
            ->hasMany(BookingCar::class, 'car_id')
            ->active();
    }

    public function scopeWhereAvailableAt($query, array $filters = [])
    {
        if (empty($filters['start_date']) || empty($filters['end_date'])) {
            return $query;
        }

        $allDates = [];

        for (
            $index = strtotime($filters['start_date']);
            $index <= strtotime($filters['end_date']);
            $index += 60 * 60 * 24
        ) {
            $allDates[] = date('Y-m-d', $index);
        }

        return $query->whereDoesntHave('activeBookingCars', function ($query) use ($allDates): void {
            $query->where(function ($query) use ($allDates): void {
                foreach ($allDates as $date) {
                    $query->orWhere(function ($query) use ($date): void {
                        $query->where('rental_start_date', '<=', $date)
                            ->where('rental_end_date', '>=', $date);
                    });
                }
            });
        });
    }

    public function getCarRentalPrice(string $startDate, string $endDate): float|int
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $days = abs($endDate->diffInDays($startDate) ?: 1);

        $ratePerDay = match ($this->rental_type) {
            CarRentalTypeEnum::PER_HOUR => $this->rental_rate * 24,
            CarRentalTypeEnum::PER_WEEK => $this->rental_rate / 7,
            default => $this->rental_rate,
        };

        return $ratePerDay * $days;
    }

    public function getImages(): array
    {
        $images = $this->images;
        if (empty($images)) {
            return [];
        }

        if (! is_array($images)) {
            $images = json_decode($images, true);
        }

        return array_filter($images);
    }

    public function getImageAttribute()
    {
        return Arr::first($this->getImages()) ?? null;
    }

    public function scopeWhereFeatured($query): Builder
    {
        return $query->where('is_featured', true);
    }

    protected function faqItems(): Attribute
    {
        return Attribute::get(function () {
            $this->loadMissing('metadata');

            $faqs = (array) $this->getMetaData('faq_schema_config', true);

            if (is_plugin_active('faq')) {
                $selectedExistingFaqs = $this->getMetaData('faq_ids', true);

                if ($selectedExistingFaqs && is_array($selectedExistingFaqs)) {
                    $selectedExistingFaqs = array_filter($selectedExistingFaqs);

                    if ($selectedExistingFaqs) {
                        $selectedFaqs = Faq::query()
                            ->wherePublished()
                            ->whereIn('id', $selectedExistingFaqs)
                            ->pluck('answer', 'question')
                            ->all();

                        foreach ($selectedFaqs as $question => $answer) {
                            $faqs[] = [
                                [
                                    'key' => 'question',
                                    'value' => $question,
                                ],
                                [
                                    'key' => 'answer',
                                    'value' => $answer,
                                ],
                            ];
                        }
                    }
                }
            }

            $faqs = array_filter($faqs);

            if (empty($faqs)) {
                return [];
            }

            foreach ($faqs as $key => $item) {
                if (! $item[0]['value'] && ! $item[1]['value']) {
                    Arr::forget($faqs, $key);
                }
            }

            return $faqs;
        })->shouldCache();
    }

    public function scopeActive(Builder $query): Builder
    {
        $query = $query->where('status', CarStatusEnum::AVAILABLE);

        $enabledPostApproval = CarRentalsHelper::isEnabledPostApproval();

        if ($enabledPostApproval) {
            $query->where('moderation_status', ModerationStatusEnum::APPROVED);
        }

        return $query;
    }

    public function newEloquentBuilder($query): BaseQueryBuilder
    {
        return new FilterCarsBuilder($query);
    }

    public function getAvgReviewAttribute(): float
    {
        return round($this->reviews()->average('star'), 2);
    }

    protected function priceText(): Attribute
    {
        return Attribute::get(function () {
            return format_price($this->rental_rate) . ' / ' . $this->rental_type->label();
        });
    }

    public function calculateTaxAmount(float $amount): float
    {
        // Calculate tax amount using the global tax settings
        $taxAmount = CarRentalsHelper::getTaxAmount($amount);

        // If global tax is not enabled but car has a specific tax, use that instead
        if ($taxAmount <= 0 && $this->tax && $this->tax->percentage > 0) {
            $taxAmount = ($amount * $this->tax->percentage / 100);
        }

        return $taxAmount;
    }

    public function getTaxInfo(float $taxAmount = 0): string
    {
        if ($taxAmount <= 0) {
            return '';
        }

        if (CarRentalsHelper::isTaxEnabled()) {
            $taxes = CarRentalsHelper::getAppliedTaxes();
            if ($taxes->isEmpty()) {
                return '';
            }

            $taxInfo = '';
            foreach ($taxes as $tax) {
                $taxInfo .= $tax->name . ' (' . $tax->percentage . '%), ';
            }

            return rtrim($taxInfo, ', ');
        } elseif ($this->tax) {
            return $this->tax->name . ' (' . $this->tax->percentage . '%)';
        }

        return '';
    }

    protected function isPendingModeration(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->exists) {
                return false;
            }

            return ! in_array($this->moderation_status, [ModerationStatusEnum::APPROVED, ModerationStatusEnum::REJECTED]);
        });
    }

    public function getCarPurposeAttribute(): string
    {
        return $this->is_for_sale ? 'sale' : 'rent';
    }

    public function setCarPurposeAttribute(string $value): void
    {
        $this->attributes['is_for_sale'] = $value === 'sale';
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'vendor_id')->withDefault();
    }

    public function hasExternalBookingUrl(): bool
    {
        return ! empty($this->external_booking_url);
    }

    public function getExternalBookingRoute(): string
    {
        return route('public.car.external-booking', $this->slug);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new ApprovedCarScope());
    }
}
