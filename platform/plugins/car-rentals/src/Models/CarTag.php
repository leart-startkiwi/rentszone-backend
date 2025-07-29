<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CarTag extends BaseModel
{
    protected $table = 'cr_tags';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'cr_car_tag', 'tag_id', 'car_id');
    }
}
