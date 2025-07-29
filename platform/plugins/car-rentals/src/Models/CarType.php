<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarType extends BaseModel
{
    use HasActiveCarsRelation;

    protected $table = 'cr_car_types';

    protected $fillable = [
        'name',
        'image',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'id', 'vehicle_type_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'vehicle_type_id', 'id');
    }
}
