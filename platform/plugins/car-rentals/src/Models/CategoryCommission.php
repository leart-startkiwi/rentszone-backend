<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryCommission extends BaseModel
{
    protected $table = 'cr_category_commissions';

    protected $fillable = [
        'car_category_id',
        'commission_percentage',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CarCategory::class, 'car_category_id');
    }
}
