<?php

namespace Botble\CarRentals\Forms\Vendor;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Supports\Language;
use Botble\CarRentals\Enums\PayoutPaymentMethodsEnum;
use Botble\CarRentals\Http\Requests\Vendor\SettingRequest;
use Botble\CarRentals\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class SettingForm extends FormAbstract
{
    public function setup(): void
    {
        $customer = $this->getModel();
        $languages = Language::getAvailableLocales();
        $payoutMethodsEnabled = PayoutPaymentMethodsEnum::payoutMethodsEnabled();

        $languages = collect($languages)
            ->pluck('name', 'locale')
            ->map(fn ($item, $key) => $item . ' - ' . $key)
            ->all();

        $this
            ->model(Customer::class)
            ->setValidatorClass(SettingRequest::class)
            ->setUrl(route('car-rentals.vendor.settings.update'))
            ->template('plugins/car-rentals::themes.vendor-dashboard.cars.form')
            ->setMethod('PUT')
            ->when(count($languages) > 1, function (FormAbstract $form) use ($languages): void {
                $form
                    ->add(
                        'locale',
                        SelectField::class,
                        SelectFieldOption::make()
                        ->label(__('Language'))
                        ->choices($languages)
                        ->selected($this->getModel()->getMetaData('locale', 'true') ?: App::getLocale())
                        ->metadata()
                        ->colspan(2)
                    );
            })
            ->add(
                'payout_payment_method',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Payment Method'))
                    ->choices(Arr::pluck($payoutMethodsEnabled, 'label', 'key'))
                    ->selected($currentPaymentMethod = old('payout_payment_method', $customer->payout_payment_method ?: PayoutPaymentMethodsEnum::BANK_TRANSFER))
                    ->colspan(2)
            );

        foreach ($payoutMethodsEnabled as $method) {
            $this->addOpenCollapsible('payout_payment_method', $method['key'], $currentPaymentMethod, ['class' => 'form-fieldset col-lg-12']);

            foreach ($method['fields'] as $key => $field) {
                $this->add(
                    "bank_info[$method[key]][$key]",
                    TextField::class,
                    TextFieldOption::make()
                        ->label($field['title'])
                        ->value(old(sprintf('bank_info.%s.%s', $method['key'], $key), Arr::get($customer->bank_info, $key)))
                        ->placeholder(Arr::get($field, 'placeholder', $field['title']))
                        ->when(isset($field['helper_text']), function (TextFieldOption $option) use ($field) {
                            return $option->helperText($field['helper_text']);
                        })
                );
            }

            $this->addCloseCollapsible('payout_payment_method', $method['key']);
        }
    }
}
