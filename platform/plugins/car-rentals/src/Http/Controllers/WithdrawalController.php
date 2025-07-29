<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\CarRentals\Forms\WithdrawalForm;
use Botble\CarRentals\Http\Requests\UpdateWithdrawalRequest;
use Botble\CarRentals\Models\Withdrawal;
use Botble\CarRentals\Tables\WithdrawalTable;
use Exception;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::withdrawal.name'), route('car-rentals.withdrawal.index'));
    }

    public function index(WithdrawalTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::withdrawal.name'));

        return $table->renderTable();
    }

    public function edit(Withdrawal $withdrawal, HttpRequest $request)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $withdrawal->customer->name]));

        event(new BeforeEditContentEvent($request, $withdrawal));

        return WithdrawalForm::createFromModel($withdrawal)->renderForm();
    }

    public function update(Withdrawal $withdrawal, UpdateWithdrawalRequest $request)
    {
        $status = $request->input('status');
        $data = [
            'status' => $status,
            'images' => array_filter((array) $request->input('images', [])),
            'user_id' => Auth::id(),
        ];

        if ($status == WithdrawalStatusEnum::REFUSED) {
            $data['description'] = $request->input('description');
            $data['refund_amount'] = $request->input('refund_amount', $withdrawal->amount);
        }

        if ($status == WithdrawalStatusEnum::COMPLETED) {
            $data['transaction_id'] = $request->input('transaction_id');
        }

        $withdrawal->fill($data);
        $withdrawal->save();

        event(new UpdatedContentEvent('WITHDRAWAL_ITEM', $request, $withdrawal));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.withdrawal.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Withdrawal $withdrawal)
    {
        try {
            $withdrawal->delete();

            return $this
                ->httpResponse()
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
