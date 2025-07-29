<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\Revenue;
use Botble\CarRentals\Tables\Formatters\PriceFormatter;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EnumColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class StoreRevenueTable extends TableAbstract
{
    protected ?int $customerId;

    protected $hasOperations = false;

    public function setup(): void
    {
        $this->model(Revenue::class);

        $this->setCustomerId(request()->route()->parameter('id'));
        $this->pageLength = 10;
        $this->type = self::TABLE_TYPE_SIMPLE;
        $this->view = $this->simpleTableView();
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->formatColumn('amount', PriceFormatter::class)
            ->formatColumn('sub_amount', PriceFormatter::class)
            ->formatColumn('fee', PriceFormatter::class);

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()
            ->select([
                'id',
                'sub_amount',
                'amount',
                'fee',
                'currency',
                'created_at',
                'type',
                'description',
            ])
            ->where('customer_id', $this->getCustomerId());

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::formatted('sub_amount')
                ->title(trans('plugins/car-rentals::revenue.sub_amount')),
            Column::formatted('fee')
                ->title(trans('plugins/car-rentals::revenue.fee')),
            Column::formatted('amount')
                ->title(trans('plugins/car-rentals::revenue.amount')),
            EnumColumn::make('type')
                ->title(trans('plugins/car-rentals::revenue.type')),
            Column::make('description')
                ->title(trans('plugins/car-rentals::revenue.description')),
            CreatedAtColumn::make(),
        ];
    }

    public function setCustomerId(int|string|null $customerId): self
    {
        $this->customerId = $customerId;
        $this->setOption('id', $this->getOption('id') . $this->customerId);

        return $this;
    }

    public function getCustomerId(): int|string|null
    {
        return $this->customerId;
    }

    public function getDefaultButtons(): array
    {
        return array_merge(['export'], parent::getDefaultButtons());
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . '$("[data-bs-toggle=tooltip]").tooltip({placement: "top", boundary: "window"});';
    }
}
