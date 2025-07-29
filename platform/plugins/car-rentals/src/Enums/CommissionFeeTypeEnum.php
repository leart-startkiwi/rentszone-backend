<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static CommissionFeeTypeEnum PERCENTAGE()
 * @method static CommissionFeeTypeEnum FIXED()
 */
class CommissionFeeTypeEnum extends Enum
{
    public const PERCENTAGE = 'percentage';
    public const FIXED = 'fixed';

    public static $langPath = 'plugins/car-rentals::enums.commission_fee_types';

    public function toHtml(): string
    {
        return match ($this->value) {
            self::PERCENTAGE => trans('plugins/car-rentals::enums.commission_fee_types.percentage'),
            self::FIXED => trans('plugins/car-rentals::enums.commission_fee_types.fixed'),
            default => parent::toHtml(),
        };
    }
}
