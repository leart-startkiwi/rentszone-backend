<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CouponTypeEnum PERCENTAGE()
 * @method static CouponTypeEnum MONEY()
 */
class CouponTypeEnum extends Enum
{
    public const PERCENTAGE = 'percentage';

    public const MONEY = 'money';

    public static $langPath = 'plugins/car-rentals::car-rentals.coupon.types';

    public function toHtml(): HtmlString|string|null
    {
        $color = match ($this->value) {
            self::MONEY => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }

    public function unit(): string
    {
        return match ($this->value) {
            self::MONEY => '$',
            self::PERCENTAGE => '%',
            default => '',
        };
    }

    public function formatValue(mixed $value)
    {
        return match ($this->value) {
            self::MONEY => sprintf('%s%s', $this->unit(), number_format($value, 2)),
            self::PERCENTAGE => sprintf('%s%s', $value, $this->unit()),
            default => $value,
        };
    }
}
