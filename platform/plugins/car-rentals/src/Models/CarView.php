<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\MassPrunable;

class CarView extends BaseModel
{
    use MassPrunable;

    protected $table = 'cr_car_views';

    protected $fillable = [
        'car_id',
        'views',
        'date',
    ];

    protected $casts = [
        'views' => 'int',
        'date' => 'date',
    ];

    public $timestamps = false;
}
