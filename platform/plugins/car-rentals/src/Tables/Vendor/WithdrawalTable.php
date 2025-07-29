<?php

namespace Botble\CarRentals\Tables\Vendor;

use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\Withdrawal;
use Botble\CarRentals\Tables\Formatters\PriceFormatter;
use Botble\CarRentals\Tables\Traits\ForVendor;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class WithdrawalTable extends TableAbstract
{
    use ForVendor;

    public function setup(): void
    {
        $this->model(Withdrawal::class);
    }

    public function buttons(): array
    {
        $customer = auth('customer')->user();
        $minimumWithdrawalAmount = CarRentalsHelper::getMinimumWithdrawalAmount();

        if ($customer->balance >= $minimumWithdrawalAmount && $customer->bank_info) {
            return $this->addCreateButton(route('car-rentals.vendor.withdrawals.create'));
        }

        return [];
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->formatColumn('fee', PriceFormatter::class)
            ->formatColumn('amount', PriceFormatter::class)
            ->addColumn('operations', function (Withdrawal $item) {
                return view('plugins/car-rentals::withdrawals.tables.actions', compact('item'))->render();
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()
            ->select([
                'id',
                'fee',
                'amount',
                'status',
                'currency',
                'created_at',
            ])
            ->where('customer_id', auth('customer')->id());

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::formatted('amount')->title(trans('plugins/car-rentals::withdrawal.amount')),
            Column::formatted('fee')->title(trans('plugins/car-rentals::withdrawal.fee')),
            CreatedAtColumn::make(),
            StatusColumn::make(),
            Column::make('operations')
                ->title(trans('core/base::tables.operations'))
                ->width(50)
                ->alignStart(),
        ];
    }
}
