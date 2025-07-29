<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\WithdrawalFeeTypeEnum;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\CarRentals\Events\WithdrawalRequested;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\VendorWithdrawalForm;
use Botble\CarRentals\Http\Requests\Fronts\VendorEditWithdrawalRequest;
use Botble\CarRentals\Http\Requests\Fronts\VendorWithdrawalRequest;
use Botble\CarRentals\Models\Withdrawal;
use Botble\CarRentals\Tables\Vendor\WithdrawalTable;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class WithdrawalController extends BaseController
{
    public function index(WithdrawalTable $table)
    {
        $this->pageTitle(__('Withdrawals'));

        return $table->renderTable();
    }

    public function create()
    {
        $user = auth('customer')->user();
        $fee = $this->calculateWithdrawalFee($user->balance);
        $minimumWithdrawal = CarRentalsHelper::getMinimumWithdrawalAmount();

        // Calculate maximum withdrawal amount
        $feeType = CarRentalsHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED);
        $feeValue = CarRentalsHelper::getSetting('fee_withdrawal', 0);

        if ($feeType === WithdrawalFeeTypeEnum::PERCENTAGE) {
            $maximum = $feeValue > 0 ? floor($user->balance / (1 + $feeValue / 100)) : $user->balance;
        } else {
            $maximum = $user->balance - $feeValue;
        }
        $maximum = max(0, $maximum);

        if ($maximum < $minimumWithdrawal || ! $user->bank_info) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(route('car-rentals.vendor.withdrawals.index'))
                ->setMessage(__('Insufficient balance or no bank information'));
        }

        $this->pageTitle(__('Withdrawal request'));

        return VendorWithdrawalForm::create()->renderForm();
    }

    public function store(VendorWithdrawalRequest $request)
    {
        $amount = $request->input('amount');
        $fee = $this->calculateWithdrawalFee($amount);
        $total = $amount + $fee;

        /**
         * @var Customer $vendor
         */
        $vendor = auth('customer')->user();

        // Double check if the total amount (including fee) exceeds the balance
        if ($total > $vendor->balance) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('The total amount (including fee) exceeds your current balance'));
        }

        try {
            DB::beginTransaction();

            /**
             * @var Withdrawal $withdrawal
             */
            $withdrawal = Withdrawal::query()->create([
                'fee' => $fee,
                'amount' => $amount,
                'customer_id' => $vendor->getKey(),
                'currency' => get_application_currency()->title,
                'bank_info' => $vendor->bank_info,
                'description' => $request->input('description'),
                'current_balance' => $vendor->balance,
                'payment_channel' => $vendor->payout_payment_method,
            ]);

            $vendor->balance -= $total;
            $vendor->save();

            event(new WithdrawalRequested($vendor, $withdrawal));

            DB::commit();
        } catch (Throwable | Exception $th) {
            DB::rollBack();

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($th->getMessage());
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.vendor.withdrawals.index'))
            ->setNextUrl(route('car-rentals.vendor.withdrawals.show', $withdrawal->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $withdrawal = Withdrawal::query()
            ->where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->where('status', WithdrawalStatusEnum::PENDING)
            ->firstOrFail();

        $this->pageTitle(__('Update withdrawal request #:id', ['id' => $id]));

        return VendorWithdrawalForm::createFromModel($withdrawal)
            ->renderForm();
    }

    public function update(int|string $id, VendorEditWithdrawalRequest $request)
    {
        $withdrawal = Withdrawal::query()
            ->where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->where('status', WithdrawalStatusEnum::PENDING)
            ->firstOrFail();

        $withdrawal->description = $request->input('description');
        $withdrawal->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.vendor.withdrawals.index'))
            ->withUpdatedSuccessMessage();
    }

    public function show(int|string $id)
    {
        $withdrawal = Withdrawal::query()
            ->where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();

        $this->pageTitle(__('View withdrawal request #:id', ['id' => $id]));

        return VendorWithdrawalForm::createFromModel($withdrawal)
            ->setUrl(route('car-rentals.vendor.withdrawals.edit', $withdrawal->getKey()))
            ->renderForm();
    }

    protected function calculateWithdrawalFee(float $amount): float
    {
        $feeType = CarRentalsHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED);
        $feeValue = CarRentalsHelper::getSetting('fee_withdrawal', 0);

        if ($feeType === WithdrawalFeeTypeEnum::PERCENTAGE) {
            return $amount * $feeValue / 100;
        }

        return $feeValue;
    }
}
