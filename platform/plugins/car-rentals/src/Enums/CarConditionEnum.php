<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CarConditionEnum NEW()
 * @method static CarConditionEnum LIKE_NEW()
 * @method static CarConditionEnum EXCELLENT()
 * @method static CarConditionEnum GOOD()
 * @method static CarConditionEnum FAIR()
 * @method static CarConditionEnum POOR()
 */
class CarConditionEnum extends Enum
{
    public const NEW = 'new';
    public const LIKE_NEW = 'like_new';
    public const EXCELLENT = 'excellent';
    public const GOOD = 'good';
    public const FAIR = 'fair';
    public const POOR = 'poor';

    public static $langPath = 'plugins/car-rentals::car-rentals.car.forms.condition_options';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::FAIR => 'warning',
            self::NEW, self::LIKE_NEW => 'success',
            self::EXCELLENT, self::GOOD => 'info',
            self::POOR => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
