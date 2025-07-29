<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

/**
 * @property string name
 * @property float percentage
 * @property BaseStatusEnum status
 * @property int priority
 */
class Tax extends BaseModel
{
    protected $table = 'cr_taxes';

    protected $fillable = [
        'name',
        'percentage',
        'status',
        'priority',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
