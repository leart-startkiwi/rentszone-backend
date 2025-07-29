<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\ServicePriceTypeEnum;

class Service extends BaseModel
{
    protected $table = 'cr_services';

    protected $fillable = [
        'name',
        'description',
        'content',
        'price',
        'price_type',
        'logo',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'price_type' => ServicePriceTypeEnum::class,
    ];
}
