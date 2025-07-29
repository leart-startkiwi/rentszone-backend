<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\CouponTypeEnum;
use Illuminate\Support\Carbon;

/**
 * @property string code
 * @property CouponTypeEnum type
 * @property double value
 * @property boolean is_unlimited_expires
 * @property Carbon expires_at
 * @property boolean is_unlimited
 * @property integer limit
 * @property integer used
 */
class Coupon extends BaseModel
{
    protected $table = 'cr_coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'is_unlimited_expires',
        'expires_at',
        'is_unlimited',
        'limit',
        'used',
    ];

    protected $casts = [
        'type' => CouponTypeEnum::class,
        'is_unlimited_expires' => 'boolean',
        'is_unlimited' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
