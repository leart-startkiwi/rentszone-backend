<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\RevenueTypeEnum;
use Botble\CarRentals\Http\Requests\StoreRevenueRequest;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Revenue;
use Botble\CarRentals\Tables\StoreRevenueTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class StoreRevenueController extends BaseController
{
    public function index(StoreRevenueTable $table)
    {
        return $table->renderTable();
    }

    public function view(int|string $id, StoreRevenueTable $table)
    {
        $customer = Customer::query()->findOrFail($id);

        abort_unless($customer->id, 404);

        Assets::addScriptsDirectly(['vendor/core/plugins/car-rentals/js/store-revenue.js']);
        $table->setAjaxUrl(route('car-rentals.store.revenue.index', $customer->id));
        $this->pageTitle(trans('plugins/car-rentals::revenue.view_vendor', ['vendor' => $customer->name]));

        return view('plugins/car-rentals::customers.index', compact('table', 'customer'))->render();
    }

    public function store(int|string $id, StoreRevenueRequest $request)
    {
        $customer = Customer::query()->findOrFail($id);

        abort_unless($customer->id, 404);

        $vendorInfo = $customer->vendorInfo;

        $amount = $request->input('amount');
        $data = [
            'fee' => 0,
            'currency' => get_application_currency()->title,
            'current_balance' => $customer->balance,
            'customer_id' => $customer->getKey(),
            'booking_id' => 0,
            'user_id' => Auth::id(),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'amount' => $amount,
            'sub_amount' => $amount,
        ];

        if ($request->input('type') == RevenueTypeEnum::ADD_AMOUNT) {
            $vendorInfo->total_revenue += $amount;
            $vendorInfo->balance += $amount;
        } else {
            $vendorInfo->total_revenue -= $amount;
            $vendorInfo->balance -= $amount;
        }

        try {
            DB::beginTransaction();

            Revenue::query()->create($data);

            $vendorInfo->save();

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($th->getMessage());
        }

        event(new UpdatedContentEvent('CUSTOMER', $request, $customer));

        return $this
            ->httpResponse()
            ->setData(['balance' => format_price($customer->balance)])
            ->setPreviousUrl(route('car-rentals.customers.index'))
            ->withUpdatedSuccessMessage();
    }
}
