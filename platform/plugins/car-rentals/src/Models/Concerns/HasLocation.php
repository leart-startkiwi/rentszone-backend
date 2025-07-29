<?php

namespace Botble\CarRentals\Models\Concerns;

use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
trait HasLocation
{
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'city_id')->withDefault();
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id')->withDefault();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id')->withDefault();
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(', ', array_filter([
                $this->detail_address,
                $this->city->name . ($this->city->zip_code ? ' ' . $this->city->zip_code : null),
                $this->state->name,
                $this->country->name,
            ])),
        );
    }
}
