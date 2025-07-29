<?php

namespace Botble\CarRentals\Facades;

use Botble\CarRentals\Supports\CurrencySupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setApplicationCurrency(\Botble\CarRentals\Models\Currency $currency)
 * @method static \Botble\CarRentals\Models\Currency|null getApplicationCurrency()
 * @method static \Botble\CarRentals\Models\Currency|null getDefaultCurrency()
 * @method static \Illuminate\Support\Collection currencies()
 * @method static string|null detectedCurrencyCode()
 * @method static array countryCurrencies()
 * @method static array currencyCodes()
 *
 * @see \Botble\CarRentals\Supports\CurrencySupport
 */
class Currency extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CurrencySupport::class;
    }
}
