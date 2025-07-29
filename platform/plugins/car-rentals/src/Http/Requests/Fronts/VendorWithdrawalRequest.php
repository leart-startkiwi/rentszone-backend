<?php

namespace Botble\CarRentals\Http\Requests\Fronts;

use Botble\CarRentals\Enums\WithdrawalFeeTypeEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\Support\Http\Requests\Request;

class VendorWithdrawalRequest extends Request
{
    public function rules(): array
    {
        $balance = auth('customer')->user()->balance;
        $fee = CarRentalsHelper::getSetting('fee_withdrawal', 0);
        $feeType = CarRentalsHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED);
        $minimumWithdrawal = CarRentalsHelper::getMinimumWithdrawalAmount();

        $rules = [
            'amount' => [
                'required',
                'numeric',
                'min:' . $minimumWithdrawal,
            ],
            'description' => [
                'nullable',
                'max:400',
            ],
        ];

        if ($feeType === WithdrawalFeeTypeEnum::PERCENTAGE) {
            $rules['amount'][] = 'max:' . ($fee > 0 ? floor($balance / (1 + $fee / 100)) : $balance);
        } else {
            $rules['amount'][] = 'max:' . ($balance - $fee);
        }

        return $rules;
    }

    public function messages(): array
    {
        $minimumWithdrawal = CarRentalsHelper::getMinimumWithdrawalAmount();

        return [
            'amount.min' => trans('plugins/car-rentals::withdrawal.forms.amount_min', [
                'min' => format_price($minimumWithdrawal),
            ]),
        ];
    }
}
