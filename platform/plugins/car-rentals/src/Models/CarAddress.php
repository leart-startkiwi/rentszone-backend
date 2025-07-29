<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Botble\CarRentals\Models\Concerns\HasLocation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarAddress extends BaseModel
{
    use HasActiveCarsRelation;
    use HasLocation;

    protected $table = 'cr_car_addresses';

    protected $fillable = [
        'detail_address',
        'status',
        'city_id',
        'state_id',
        'country_id',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'id', 'vehicle_type_id');
    }
}
