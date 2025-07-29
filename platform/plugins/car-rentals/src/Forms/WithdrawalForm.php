<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\CarRentals\Http\Requests\UpdateWithdrawalRequest;
use Botble\CarRentals\Models\Withdrawal;

class WithdrawalForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Withdrawal::class)
            ->setValidatorClass(UpdateWithdrawalRequest::class)
            ->withCustomFields()
            ->add('customer_id', 'html', [
                'html' => sprintf(
                    '<input type="hidden" name="customer_id" value="%s">
                    <div class="form-group mb-3">
                        <label class="control-label">%s</label>
                        <input type="text" class="form-control" value="%s" readonly>
                    </div>',
                    $this->getModel()->customer->id,
                    trans('plugins/car-rentals::customer.name'),
                    $this->getModel()->customer->name
                ),
            ])
            ->add('images[]', 'mediaImages', [
                'label' => trans('plugins/car-rentals::withdrawal.images'),
                'value' => $this->getModel()->images,
            ])
            ->add('amount', 'html', [
                'html' => sprintf(
                    '<div class="form-group mb-3">
                        <label class="control-label">%s</label>
                        <input type="text" class="form-control" value="%s" readonly>
                    </div>',
                    trans('plugins/car-rentals::withdrawal.amount'),
                    format_price($this->getModel()->amount)
                ),
            ])
            ->add('fee', 'html', [
                'html' => sprintf(
                    '<div class="form-group mb-3">
                        <label class="control-label">%s</label>
                        <input type="text" class="form-control" value="%s" readonly>
                    </div>',
                    trans('plugins/car-rentals::withdrawal.fee'),
                    format_price($this->getModel()->fee)
                ),
            ])
            ->add('payment_channel', 'html', [
                'html' => sprintf(
                    '<div class="form-group mb-3">
                        <label class="control-label">%s</label>
                        <input type="text" class="form-control" value="%s" readonly>
                    </div>',
                    trans('plugins/car-rentals::withdrawal.payment_channel'),
                    $this->getModel()->payment_channel->label()
                ),
            ])
            ->add('transaction_id', TextField::class, [
                'label' => trans('plugins/car-rentals::withdrawal.transaction_id'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/car-rentals::withdrawal.transaction_id'),
                    'data-counter' => 60,
                ],
            ])
            ->add('description', TextareaField::class, [
                'label' => trans('core/base::forms.description'),
                'attr' => [
                    'rows' => 3,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 200,
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(
                $this->getModel()->getNextStatuses()
            )->toArray())
            ->add('refund_amount', 'html', [
                'html' => sprintf(
                    '<div class="form-group mb-3 refund-amount-wrapper" style="display: none">
                        <label class="control-label required">%s</label>
                        <input type="number" class="form-control" name="refund_amount" value="%s">
                    </div>',
                    trans('plugins/car-rentals::withdrawal.refund_amount'),
                    $this->getModel()->amount
                ),
            ])
            ->add('invoice_buttons', 'html', [
                'html' => $this->getModel()->status == WithdrawalStatusEnum::COMPLETED
                    ? view('plugins/car-rentals::withdrawals.download-invoice', ['withdrawal' => $this->getModel()])->render()
                    : '',
            ])
            ->setBreakFieldPoint('status');
    }

    public function getScripts(): string
    {
        return parent::getScripts() . '
            <script>
                $(document).ready(function() {
                    $(document).on("change", "#status", function() {
                        if ($(this).val() == "' . WithdrawalStatusEnum::REFUSED . '") {
                            $(".refund-amount-wrapper").show();
                            $("#transaction_id-group").hide();
                        } else if ($(this).val() == "' . WithdrawalStatusEnum::COMPLETED . '") {
                            $(".refund-amount-wrapper").hide();
                            $("#transaction_id-group").show();
                        } else {
                            $(".refund-amount-wrapper").hide();
                            $("#transaction_id-group").hide();
                        }
                    });

                    $("#status").trigger("change");
                });
            </script>
        ';
    }
}
