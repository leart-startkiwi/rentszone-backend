<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarMake extends BaseModel
{
    protected $table = 'cr_car_makes';

    protected $fillable = [
        'name',
        'logo',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'make_id', 'id');
    }
}
