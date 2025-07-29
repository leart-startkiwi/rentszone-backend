<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static BookingStatusEnum PENDING()
 * @method static BookingStatusEnum PROCESSING()
 * @method static BookingStatusEnum COMPLETED()
 * @method static BookingStatusEnum CANCELLED()
 */
class BookingStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const PROCESSING = 'processing';

    public const COMPLETED = 'completed';

    public const CANCELLED = 'cancelled';

    public static $langPath = 'plugins/car-rentals::booking.statuses';

    public function getColor(): string
    {
        return match ($this->value) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
            default => 'primary',
        };
    }

    public function toHtml(): HtmlString|string|null
    {
        return BaseHelper::renderBadge($this->label(), $this->getColor());
    }
}
