<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarMaintenanceHistory extends BaseModel
{
    protected $table = 'cr_car_maintenance_histories';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'currency_id',
        'car_id',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
