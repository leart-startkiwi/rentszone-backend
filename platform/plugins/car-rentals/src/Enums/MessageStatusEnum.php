<?php

namespace Botble\CarRentals\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static MessageStatusEnum UNREAD()
 * @method static MessageStatusEnum READ()
 */
class MessageStatusEnum extends Enum
{
    public const READ = 'read';

    public const UNREAD = 'unread';

    public static $langPath = 'plugins/car-rentals::message.statuses';

    public function toHtml(): HtmlString|string|null
    {
        $color = match ($this->value) {
            self::UNREAD => 'warning',
            self::READ => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
