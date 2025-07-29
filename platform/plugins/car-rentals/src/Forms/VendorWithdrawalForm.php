<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Enums\WithdrawalFeeTypeEnum;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Fronts\VendorEditWithdrawalRequest;
use Botble\CarRentals\Http\Requests\Fronts\VendorWithdrawalRequest;
use Botble\CarRentals\Models\Withdrawal;

class VendorWithdrawalForm extends FormAbstract
{
    public function setup(): void
    {
        $fee = CarRentalsHelper::getSetting('fee_withdrawal', 0);
        $feeType = CarRentalsHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED);

        $exists = $this->getModel() && $this->getModel()->id;

        $actionButtons = view('plugins/car-rentals::withdrawals.forms.actions')->render();
        if ($exists) {
            $fee = null;
            if (! $this->getModel()->vendor_can_edit) {
                $actionButtons = ' ';
            }
        }

        $user = auth('customer')->user();
        $model = $user;
        $balance = $model->balance;
        $paymentChannel = $model->payout_payment_method;

        if ($exists) {
            $model = $this->getModel();
            $paymentChannel = $model->payment_channel;
        }

        $bankInfo = $model->bank_info;

        $feeHelperText = null;
        $maximum = 0;

        if (! $exists) {
            $feeHelperText = trans('plugins/car-rentals::withdrawal.forms.fee_helper', ['fee' => format_price($fee)]);
            $feeHelperText .= '<br>' . trans('plugins/car-rentals::withdrawal.forms.minimum_withdrawal_amount', ['amount' => format_price(CarRentalsHelper::getMinimumWithdrawalAmount())]);

            // Calculate maximum withdrawal amount
            if ($feeType === WithdrawalFeeTypeEnum::PERCENTAGE) {
                $maximum = $fee > 0 ? floor($balance / (1 + $fee / 100)) : $balance;
            } else {
                $maximum = $balance - $fee;
            }
            $maximum = max(0, $maximum);

            $feeHelperText .= '<br>' . trans('plugins/car-rentals::withdrawal.forms.you_will_get', ['amount' => format_price($maximum)]);
        }

        $this
            ->model(Withdrawal::class)
            ->setValidatorClass($exists ? VendorEditWithdrawalRequest::class : VendorWithdrawalRequest::class)
            ->template(CarRentalsHelper::viewPath('vendor-dashboard.forms.base'))
            ->add(
                'amount',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::withdrawal.forms.amount_with_balance', ['balance' => format_price($balance)]))
                    ->required()
                    ->placeholder(trans('plugins/car-rentals::withdrawal.forms.amount_placeholder'))
                    ->attributes([
                        'data-counter' => 120,
                        'max' => $maximum,
                        'min' => CarRentalsHelper::getMinimumWithdrawalAmount(),
                    ])
                    ->disabled($exists)
                    ->helperText($feeHelperText)
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/car-rentals::withdrawal.forms.description'))
                    ->placeholder(trans('plugins/car-rentals::withdrawal.forms.description_placeholder'))
                    ->rows(3)
                    ->attributes([
                        'disabled' => $exists && $this->getModel()->status != WithdrawalStatusEnum::PENDING,
                    ])
            )
            ->add('fee', 'html', [
                'html' => $exists
                    ? view('plugins/car-rentals::withdrawals.payout-info', [
                        'bankInfo' => $bankInfo,
                        'paymentChannel' => $paymentChannel,
                        'link' => route('car-rentals.vendor.settings.index') . '#payout-info',
                    ])->render()
                    : view('plugins/car-rentals::withdrawals.payout-info', [
                        'bankInfo' => $bankInfo,
                        'paymentChannel' => $paymentChannel,
                        'title' => trans('plugins/car-rentals::withdrawal.forms.your_bank_info'),
                        'link' => route('car-rentals.vendor.settings.index') . '#payout-info',
                    ])->render(),
            ])
            ->setActionButtons($actionButtons);

        if ($exists && $this->getModel()->images) {
            $this->add('images', 'html', [
                'html' => view('plugins/car-rentals::withdrawals.forms.images', ['model' => $this->getModel()])->render(),
            ]);
        }

        if ($exists && $this->getModel()->status == WithdrawalStatusEnum::COMPLETED) {
            $this->add('invoice', 'html', [
                'html' => view('plugins/car-rentals::withdrawals.download-invoice', ['withdrawal' => $this->getModel()])->render(),
            ]);
        }
    }
}
