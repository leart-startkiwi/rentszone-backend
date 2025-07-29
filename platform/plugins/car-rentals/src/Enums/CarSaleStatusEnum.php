<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CarSaleStatusEnum PENDING()
 * @method static CarSaleStatusEnum PROCESSING()
 * @method static CarSaleStatusEnum COMPLETED()
 * @method static CarSaleStatusEnum CANCELLED()
 */
class CarSaleStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const PROCESSING = 'processing';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public static $langPath = 'plugins/car-rentals::car-rentals.car_sale.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
