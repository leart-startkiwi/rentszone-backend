<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateWithdrawalRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'status' => Rule::in(WithdrawalStatusEnum::values()),
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'description' => 'nullable|max:255',
        ];

        if ($this->input('status') == WithdrawalStatusEnum::COMPLETED) {
            $rules['transaction_id'] = 'required|max:120';
        }

        if ($this->input('status') == WithdrawalStatusEnum::REFUSED) {
            $rules['refund_amount'] = 'required|numeric|min:0|not_in:0';
        }

        return $rules;
    }
}
