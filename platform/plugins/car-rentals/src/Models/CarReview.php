<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarReview extends BaseModel
{
    use HasActiveCarsRelation;

    protected $table = 'cr_car_reviews';

    protected $fillable = [
        'car_id',
        'customer_id',
        'star',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'content' => SafeContent::class,
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
