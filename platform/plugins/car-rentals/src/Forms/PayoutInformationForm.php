<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Models\BaseModel;
use Botble\CarRentals\Enums\PayoutPaymentMethodsEnum;
use Botble\CarRentals\Http\Requests\PayoutInformationSettingRequest;
use Illuminate\Support\Arr;

class PayoutInformationForm extends FormAbstract
{
    public function setup(): void
    {
        $customer = $this->getModel();
        $payoutMethodsEnabled = PayoutPaymentMethodsEnum::payoutMethodsEnabled();

        $this
            ->model(BaseModel::class)
            ->setValidatorClass(PayoutInformationSettingRequest::class)
            ->contentOnly()
            ->add('payout_payment_method', SelectField::class, [
                'label' => __('Payment Method'),
                'selected' => $customer->payout_payment_method,
                'choices' => Arr::pluck($payoutMethodsEnabled, 'label', 'key'),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.payout-payment-wrapper',
                ],
            ]);

        foreach ($payoutMethodsEnabled as $method) {
            $hidden = old('payout_payment_method', $customer->payout_payment_method ?: PayoutPaymentMethodsEnum::BANK_TRANSFER) != $method['key'];

            $this->add("openWrapper_{$method['key']}", HtmlField::class, [
                'html' => "<div id='{$method['key']}' data-bb-value='{$method['key']}' class='payout-payment-wrapper'" . ($hidden ? ' style="display: none"' : '') . '>',
            ]);

            foreach ($method['fields'] as $key => $field) {
                $fieldOption = TextFieldOption::make()
                    ->label($field['title'])
                    ->value(old(sprintf('bank_info.%s.%s', $method['key'], $key), Arr::get($customer->bank_info, $key)))
                    ->placeholder(Arr::get($field, 'placeholder', $field['title']))
                    ->when(isset($field['helper_text']), function (TextFieldOption $option) use ($field) {
                        return $option->helperText($field['helper_text']);
                    });

                $this->add("bank_info[$method[key]][$key]", TextField::class, $fieldOption);
            }

            $this->add("closeWrapper_{$method['key']}", HtmlField::class, [
                'html' => '</div>',
            ]);
        }

        $this->addSubmitButton(__('Save settings'));
    }

    protected function addSubmitButton(string $label = null): self
    {
        $label = $label ?: trans('core/base::forms.save');

        $this->add('submit', 'html', [
            'html' => view('core/base::forms.partials.form-actions', compact('label'))->render(),
        ]);

        return $this;
    }
}
