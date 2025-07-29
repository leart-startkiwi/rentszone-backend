<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;

/**
 * @method static CarRentalTypeEnum PER_HOUR()
 * @method static CarRentalTypeEnum PER_DAY()
 * @method static CarRentalTypeEnum PER_WEEK()
 * @method static CarRentalTypeEnum PER_MONTH()
 */
class CarRentalTypeEnum extends Enum
{
    public const PER_HOUR = 'per_hour';
    public const PER_DAY = 'per_day';
    public const PER_WEEK = 'per_week';
    public const PER_MONTH = 'per_month';

    public static $langPath = 'plugins/car-rentals::car-rentals.car.enums.rental_types';

    public function toHtml(): string
    {
        return BaseHelper::renderBadge($this->label());
    }

    public function shortLabel(): string
    {
        return match ($this->value) {
            self::PER_HOUR => __('Hour'),
            self::PER_DAY => __('Day'),
            self::PER_WEEK => __('Week'),
            self::PER_MONTH => __('Month'),
            default => '',
        };
    }
}
