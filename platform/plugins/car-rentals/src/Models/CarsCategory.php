<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;

class CarsCategory extends BaseModel
{
    protected $table = 'cr_cars_categories';

    protected $fillable = [
        'cr_car_category_id',
        'cr_car_id',
    ];

    public $timestamps = false;
}
