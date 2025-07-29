<?php

namespace Botble\CarRentals\Http\Requests\Fronts;

use Botble\Base\Rules\EmailRule;
use Botble\CarRentals\Models\Customer;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class CheckoutRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'service_ids' => ['nullable', 'array'],
        ];

        if (is_plugin_active('payment')) {
            $paymentMethods = Arr::where(PaymentMethodEnum::values(), function ($value) {
                return (int) get_payment_setting('status', $value) == 1;
            });

            $rules['payment_method'] = 'sometimes|' . Rule::in($paymentMethods);
        }

        $rules['customer_name'] = ['required', 'string'];
        $rules['customer_email'] = ['required', 'email'];
        $rules['customer_phone'] = ['required', 'string'];

        $isCreateAccount = ! auth('customer')->check() && $this->input('create_account') == 1;
        if ($isCreateAccount) {
            $rules['password'] = 'required|min:6';
            $rules['password_confirmation'] = 'required|same:password';
            $rules['customer_email'] = ['required', new EmailRule(), Rule::unique((new Customer())->getTable(), 'email')];
            $rules['customer_name'] = 'required|min:3|max:120';
        }

        return $rules;
    }
}
