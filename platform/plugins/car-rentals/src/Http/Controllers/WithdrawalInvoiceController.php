<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\CarRentals\Models\Withdrawal;
use Botble\CarRentals\Services\GeneratePayoutInvoiceService;
use Illuminate\Http\Request;

class WithdrawalInvoiceController extends BaseController
{
    public function __invoke(
        Withdrawal $withdrawal,
        Request $request,
        GeneratePayoutInvoiceService $generateWithdrawalInvoiceService
    ) {
        abort_if($withdrawal->status != WithdrawalStatusEnum::COMPLETED, 404);

        $generateWithdrawalInvoiceService->withdrawal($withdrawal->loadMissing('customer'));

        if ($request->input('type') === 'print') {
            return $generateWithdrawalInvoiceService->stream();
        }

        return $generateWithdrawalInvoiceService->download();
    }
}
