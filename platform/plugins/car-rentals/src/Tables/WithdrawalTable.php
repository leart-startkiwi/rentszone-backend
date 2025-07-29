<?php

namespace Botble\CarRentals\Tables;

use Botble\Base\Facades\Html;
use Botble\CarRentals\Enums\WithdrawalStatusEnum;
use Botble\CarRentals\Models\Withdrawal;
use Botble\CarRentals\Tables\Formatters\PriceFormatter;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
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
    public function setup(): void
    {
        $this
            ->model(Withdrawal::class)
            ->addActions([
                EditAction::make()->route('car-rentals.withdrawal.edit'),
                DeleteAction::make()->route('car-rentals.withdrawal.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer_id', function (Withdrawal $item) {
                if (! $item->customer->id) {
                    return '&mdash;';
                }

                return Html::link(route('car-rentals.customers.edit', $item->customer->id), $item->customer->name);
            })
            ->formatColumn('fee', PriceFormatter::class)
            ->formatColumn('amount', PriceFormatter::class);

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()
            ->query()
            ->select([
                'id',
                'customer_id',
                'amount',
                'fee',
                'status',
                'created_at',
            ])
            ->with(['customer']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_id')
                ->title(trans('plugins/car-rentals::withdrawal.customer_name'))
                ->alignLeft(),
            Column::formatted('amount')
                ->title(trans('plugins/car-rentals::withdrawal.amount'))
                ->alignEnd(),
            Column::formatted('fee')
                ->title(trans('plugins/car-rentals::withdrawal.fee'))
                ->alignEnd(),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('car-rentals.withdrawal.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => WithdrawalStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', WithdrawalStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
