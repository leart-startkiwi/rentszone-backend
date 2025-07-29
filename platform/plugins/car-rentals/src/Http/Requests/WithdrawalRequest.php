<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\CarRentals\Enums\PayoutPaymentMethodsEnum;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WithdrawalRequest extends Request
{
    public function rules(): array
    {
        return [
            'images' => ['nullable', 'array'],
            'status' => Rule::in(WithdrawalStatusEnum::values()),
            'description' => ['nullable', 'max:400'],
            'payment_channel' => Rule::in(array_keys(PayoutPaymentMethodsEnum::payoutMethodsEnabled())),
        ];
    }
}
