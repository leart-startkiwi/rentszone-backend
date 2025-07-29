<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarFuel extends BaseModel
{
    use HasActiveCarsRelation;

    protected $table = 'cr_car_fuels';

    protected $fillable = [
        'name',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'id', 'fuel_type_id');
    }
}
