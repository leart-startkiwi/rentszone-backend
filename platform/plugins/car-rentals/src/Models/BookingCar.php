<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingCar extends BaseModel
{
    protected $table = 'cr_booking_cars';

    protected $fillable = [
        'car_image',
        'car_name',
        'price',
        'currency_id',
        'rental_start_date',
        'rental_end_date',
        'rental_end_date',
        'booking_id',
        'car_id',
        'pickup_address_id',
        'return_address_id',
    ];

    protected $casts = [
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id')->withDefault();
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id')->withDefault();
    }

    public function pickupAddress(): HasOne
    {
        return $this->hasOne(CarAddress::class, 'id', 'pickup_address_id');
    }

    public function returnAddress(): HasOne
    {
        return $this->hasOne(CarAddress::class, 'id', 'return_address_id');
    }

    public function pickupAddressText(): Attribute
    {
        return Attribute::get(function () {
            return $this->pickupAddress->full_address ?? '';
        });
    }

    public function returnAddressText(): Attribute
    {
        return Attribute::get(function () {
            return $this->returnAddress->full_address ?? '';
        });
    }

    public function scopeActive($query)
    {
        return $query
            ->whereHas('booking', fn ($query) => $query->whereNotIn('status', [BookingStatusEnum::CANCELLED]));
    }

    protected function bookingPeriod(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['rental_start_date'] . ' -> ' . $attributes['rental_end_date'],
        );
    }
}
