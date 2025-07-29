<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\CarRentals\Enums\CouponTypeEnum;
use Botble\CarRentals\Http\Requests\Rules\CouponValueRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CouponRequest extends Request
{
    public function rules(): array
    {
        return [
            'code' => 'nullable|string|required_if:type,coupon|max:20|unique:cr_coupons,code,' . $this->route('coupon.id'),
            'value' => ['required', 'string', 'numeric', 'min:0', new CouponValueRule($this->input('type', ''))],
            'type' => ['required', Rule::in(CouponTypeEnum::values())],
            'limit' => [
                Rule::requiredIf(function () {
                    return ! $this->boolean('is_unlimited');
                }),
                'nullable',
                'numeric',
                'min:1',
            ],
            'end_date' => ['nullable', 'date'],
            'end_time' => ['nullable', 'string'],
        ];
    }
}
