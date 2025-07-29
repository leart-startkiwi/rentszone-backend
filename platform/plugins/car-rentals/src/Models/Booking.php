<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Booking extends BaseModel
{
    protected $table = 'cr_bookings';

    protected $fillable = [
        'booking_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_age',
        'customer_id',
        'vendor_id',
        'amount',
        'sub_total',
        'coupon_amount',
        'coupon_code',
        'tax_amount',
        'currency_id',
        'payment_id',
        'note',
        'status',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
    ];

    public function car(): HasOne
    {
        return $this->hasOne(BookingCar::class, 'booking_id')->withDefault();
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'reference_id')->withDefault();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'vendor_id')->withDefault();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'cr_booking_service', 'booking_id', 'service_id');
    }

    public static function generateUniqueBookingNumber(): string
    {
        $nextInsertId = BaseModel::determineIfUsingUuidsForId() ?
            static::query()->count() + 1 :
            static::query()->max('id') + 1;

        do {
            $code = CarRentalsHelper::getBookingNumber($nextInsertId);
            $nextInsertId++;
        } while (static::query()->where('booking_number', $code)->exists());

        return $code;
    }

    public static function getRevenueData(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        $select = []
    ): Collection {
        if (empty($select)) {
            $select = [
                DB::raw('DATE(payments.created_at) AS date'),
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
            ];
        }

        return self::query()
            ->join('payments', 'payments.id', '=', 'cr_bookings.payment_id')
            ->whereDate('payments.created_at', '>=', $startDate)
            ->whereDate('payments.created_at', '<=', $endDate)
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->groupBy('date')
            ->select($select)
            ->get();
    }
}
