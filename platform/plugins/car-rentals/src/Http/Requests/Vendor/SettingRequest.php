<?php

namespace Botble\CarRentals\Http\Requests\Vendor;

use Botble\Base\Supports\Language;
use Botble\CarRentals\Enums\PayoutPaymentMethodsEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class SettingRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $channel = $this->input('payout_payment_method');

        if ($channel) {
            $this->merge(['bank_info' => [$channel => Arr::get($this->input('bank_info'), $channel)]]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'locale' => ['sometimes', 'required', Rule::in(array_keys(Language::getAvailableLocales()))],
        ];

        return array_merge($rules, PayoutPaymentMethodsEnum::getRules('bank_info'));
    }

    public function attributes(): array
    {
        return array_merge([
            'bank_info' => __('Payout info'),
        ], PayoutPaymentMethodsEnum::getAttributes('bank_info'));
    }

    protected function passedValidation(): void
    {
        $channel = $this->input('payout_payment_method');

        if ($channel) {
            $this->merge(['bank_info' => Arr::get($this->input('bank_info'), $channel)]);
        }
    }
}
