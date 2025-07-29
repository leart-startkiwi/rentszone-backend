<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CarForSaleStatusEnum AVAILABLE()
 * @method static CarForSaleStatusEnum SOLD()
 * @method static CarForSaleStatusEnum RESERVED()
 */
class CarForSaleStatusEnum extends Enum
{
    public const AVAILABLE = 'available';
    public const SOLD = 'sold';
    public const RESERVED = 'reserved';

    public static $langPath = 'plugins/car-rentals::car-rentals.car.forms.sale_statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::RESERVED => 'warning',
            self::AVAILABLE => 'success',
            self::SOLD => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
