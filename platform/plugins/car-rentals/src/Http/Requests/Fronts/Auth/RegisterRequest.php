<?php

namespace Botble\CarRentals\Http\Requests\Fronts\Auth;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\CarRentals\Models\Customer;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'max:120', 'min:2'],
            'email' => [
                'nullable',
                new EmailRule(),
                Rule::unique((new Customer())->getTable()),
            ],
            'phone' => [
                'nullable',
                ...explode('|', BaseHelper::getPhoneValidationRule()),
                Rule::unique((new Customer())->getTable(), 'phone'),
            ],
            'password' => ['required', 'min:6', 'confirmed'],
            'is_vendor' => ['sometimes', 'boolean'],
        ];

        if (get_car_rentals_setting('show_terms_and_policy_acceptance_checkbox', true)) {
            $rules['agree_terms_and_policy'] = ['required', 'accepted:1'];
        }

         if (request()->is('api/*')) {
             \Log::info('leart37');
        return $rules;
    }

        return apply_filters('car_rentals_customer_registration_form_validation_rules', $rules);
    }

    public function attributes(): array
    {
        return apply_filters('car_rentals_customer_registration_form_validation_attributes', [
            'name' => __('Name'),
            'email' => __('Email'),
            'password' => __('Password'),
            'phone' => __('Phone'),
            'agree_terms_and_policy' => __('Term and Policy'),
        ]);
    }

    public function messages(): array
    {
        return apply_filters('car_rentals_customer_registration_form_validation_messages', []);
    }
}
