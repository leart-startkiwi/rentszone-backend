<?php

namespace Botble\CarRentals\Services;

use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\CouponTypeEnum;
use Botble\CarRentals\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CouponService
{
    public function getCouponByCode(string $code): BaseModel|Model|null
    {
        return Coupon::query()
            ->where('code', $code)
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', Carbon::now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('limit')
                    ->orWhereColumn('limit', '>', 'used');
            })
            ->first();
    }

    public function getDiscountAmount(string $type, float $value, float $amountTotal = 0): float
    {
        return match ($type) {
            CouponTypeEnum::PERCENTAGE => $value / 100 * $amountTotal,
            CouponTypeEnum::MONEY => $value,
            default => 0,
        };
    }
}
