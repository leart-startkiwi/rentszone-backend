<?php

namespace Botble\CarRentals\Models;

use Botble\ACL\Models\User;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\RevenueTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revenue extends BaseModel
{
    protected $table = 'cr_customer_revenues';

    protected $fillable = [
        'customer_id',
        'booking_id',
        'sub_amount',
        'fee',
        'amount',
        'current_balance',
        'currency',
        'description',
        'user_id',
        'type',
    ];

    protected $casts = [
        'type' => RevenueTypeEnum::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class)->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency', 'title')->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function getDescriptionTooltipAttribute(): string
    {
        if (! $this->description) {
            return '';
        }

        return Html::tag('span', BaseHelper::renderIcon('ti ti-info-circle'), [
            'class' => 'ms-1 text-info',
            'data-bs-toggle' => 'tooltip',
            'data-bs-original-title' => $this->description,
            'title' => $this->description,
        ]);
    }
}
