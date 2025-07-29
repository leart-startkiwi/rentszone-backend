<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CarStatusEnum AVAILABLE()
 * @method static CarStatusEnum RENTED()
 * @method static CarStatusEnum MAINTENANCE()
 * @method static CarStatusEnum OUT_OF_SERVICE()
 */
class CarStatusEnum extends Enum
{
    public const AVAILABLE = 'available';

    public const RENTED = 'rented';

    public const MAINTENANCE = 'maintenance';

    public const OUT_OF_SERVICE = 'out_of_service';

    public static $langPath = 'plugins/car-rentals::car-rentals.car.enums.statuses';

    public function toHtml(): HtmlString|string|null
    {
        $color = match ($this->value) {
            self::MAINTENANCE => 'warning',
            self::RENTED => 'info',
            self::AVAILABLE => 'success',
            self::OUT_OF_SERVICE => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
