<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CarPurposeEnum FOR_SALE()
 * @method static CarPurposeEnum FOR_RENT()
 */
class CarPurposeEnum extends Enum
{
    public const FOR_SALE = 'sale';
    public const FOR_RENT = 'rent';

    public static $langPath = 'plugins/car-rentals::car-rentals.car.car_purposes';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::FOR_SALE => 'info',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
