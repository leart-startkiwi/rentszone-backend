<?php

namespace Botble\CarRentals\Http\Requests\Rules;

use Botble\CarRentals\Enums\CouponTypeEnum;
use Illuminate\Contracts\Validation\Rule;

class CouponValueRule implements Rule
{
    public function __construct(
        private readonly string $type,
    ) {
    }

    public function passes($attribute, $value): bool
    {
        if ($this->type !== CouponTypeEnum::PERCENTAGE) {
            return true;
        }

        return $value < 100;
    }

    public function message(): string
    {
        return 'The percentage value of the coupon must be less than 100';
    }
}
