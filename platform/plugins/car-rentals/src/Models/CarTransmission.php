<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarTransmission extends BaseModel
{
    use HasActiveCarsRelation;

    protected $table = 'cr_car_transmissions';

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
        return $this->belongsTo(Car::class, 'id', 'transmission_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'transmission_id', 'id');
    }
}
