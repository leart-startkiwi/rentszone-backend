<?php

namespace Botble\CarRentals\Facades;

use Botble\CarRentals\Supports\BookingSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed|null getCheckoutData(string|null $key = null)
 * @method static void saveCheckoutData(array $data)
 *
 * @see \Botble\CarRentals\Supports\BookingSupport
 */
class BookingHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BookingSupport::class;
    }
}
