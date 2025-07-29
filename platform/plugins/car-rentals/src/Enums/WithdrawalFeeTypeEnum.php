<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static WithdrawalFeeTypeEnum FIXED()
 * @method static WithdrawalFeeTypeEnum PERCENTAGE()
 */
class WithdrawalFeeTypeEnum extends Enum
{
    public const FIXED = 'fixed';
    public const PERCENTAGE = 'percentage';

    public static $langPath = 'plugins/car-rentals::car-rentals.settings.withdrawal_fee_types';

    public function toHtml(): string
    {
        return match ($this->value) {
            self::FIXED => trans('plugins/car-rentals::car-rentals.settings.withdrawal_fee_types.fixed'),
            self::PERCENTAGE => trans('plugins/car-rentals::car-rentals.settings.withdrawal_fee_types.percentage'),
            default => parent::toHtml(),
        };
    }
}
