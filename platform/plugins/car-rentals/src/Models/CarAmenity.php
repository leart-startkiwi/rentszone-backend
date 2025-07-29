<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CarAmenity extends BaseModel
{
    use HasActiveCarsRelation;

    protected $table = 'cr_car_amenities';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'cr_cars_amenities', 'cr_car_amenity_id', 'cr_car_id');
    }
}
